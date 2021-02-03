<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../vendor/Box/Spout/Autoloader/autoload.php';
require_once dirname(__FILE__) . '/../Data/ExportEnum.php';
require_once dirname(__FILE__) . '/../FTP/SFTP.php';
require_once dirname(__FILE__) . '/../FTP/FTP.php';
require_once dirname(__FILE__) . '/../Data/SaveType.php';
include_once 'ExportInterface.php';

class Export
{
    private $hasAttr;
    private $lastElement;
    private $rowsNumber;
    private $currentColor = 'white';
    private $moduleTools;

    public $mime_attachment = array(
        'csv' => 'text/csv',
        'xlsx' => 'application/vnd.ms-excel',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    public function __construct()
    {
        $this->moduleTools = new ModuleTools();
    }


    /**
     * Add application static methods for easy tests
     * @return ModuleTools
     */
    private function getModuleTools()
    {
        return $this->moduleTools;
    }

    public function createExportFile($ae)
    {
        ini_set('memory_limit', '725M');
        $sorted_fields = $this->getLabelsAndFields($ae->type, $this->decodeFields($ae->fields));

        $this->cleanProgress();

        $entityExportObject = $this->getEntityExportObject($ae, $sorted_fields);

        $file_path = $this->writeToFile(
            $ae,
            $sorted_fields,
            $entityExportObject->getEntityData(),
            $entityExportObject
        );

        $this->saveLastExportId($ae, $this->lastElement);

        $this->processFile($ae, $file_path);
    }

    private function decodeFields($fields)
    {
        return json_decode($fields, true);
    }

    public function cleanProgress()
    {
        $this->saveProgressToFile(0, true);
    }

    public function getLabelsAndFields($type, $specific_settings)
    {
        $added = false; // for orders add must have fields to export all

        if ($specific_settings) {
            set_time_limit(0);
            $allFields = AdvancedExportFieldClass::getAllFields($type);

            foreach ($specific_settings['fields[]'] as $field => $name) {
                $this->getAllFieldsAndLabels($allFields, $field, $name, $specific_settings);
                $this->sortOtherFields($allFields, $field, $specific_settings);
                $this->sortStaticFields($allFields, $field, $specific_settings);
                $this->sortSqlFields($allFields, $field, $specific_settings);
                $this->sortAttributes($allFields, $field, $specific_settings);
                $this->addRequiredOrderDetailFields($allFields, $field, $specific_settings, $added);
            }
        }

        return $specific_settings;
    }

    /**
     * @param array $allFields
     * @param $field
     * @param array $specific_settings
     * @return array
     */
    public function sortOtherFields(array $allFields, $field, array &$specific_settings)
    {
        if ($this->ifOtherAndAttributesFalse($allFields, $field)) {
            $specific_settings['otherfields'][$allFields[$field]['field']]['field'] = $allFields[$field]['field'];
            $specific_settings['otherfields'][$allFields[$field]['field']]['isCustom'] = $allFields[$field]['isCustom'];
        }
        return $specific_settings;
    }

    private function ifOtherAndAttributesFalse($allFields, $field)
    {
        return $allFields[$field]['table'] == 'other' && $allFields[$field]['attribute'] == false;
    }

    /**
     * @param array $allFields
     * @param $field
     * @param array $specific_settings
     * @return array
     */
    public function sortStaticFields(array $allFields, $field, array &$specific_settings)
    {
        if ($allFields[$field]['table'] == 'static') {
            $specific_settings['static'][$allFields[$field]['field']] = $allFields[$field]['return'];
        }
        return $specific_settings;
    }

    /**
     * @param array $allFields
     * @param $field
     * @param array $specific_settings
     * @param $added
     */
    public function addRequiredOrderDetailFields(array $allFields, $field, array &$specific_settings, &$added)
    {
        if ($allFields[$field]['table'] === 'order_detail' && !$added) {
            $added = true;
            $specific_settings['order_detail'] = true;
            if (!in_array('od.`product_id`', $specific_settings['sqlfields'])) {
                $specific_settings['sqlfields'][] = 'od.`product_id`';
            }
            $specific_settings['sqlfields'][] = 'od.`product_attribute_id`';
            $specific_settings['sqlfields'][] = 'o.`id_cart`';
        }
    }

    /**
     * @param array $allFields
     * @param $field
     * @param array $specific_settings
     */
    public function sortAttributes(array $allFields, $field, array &$specific_settings)
    {
        if (isset($allFields[$field]['attribute']) && $allFields[$field]['attribute'] == true) {
            $specific_settings['attribute_fields'][] = $allFields[$field]['field'];
        }
    }

    /**
     * @param array $allFields
     * @param $field
     * @param array $specific_settings
     */
    public function sortSqlFields(array $allFields, $field, array &$specific_settings)
    {
        if ($allFields[$field]['attribute'] == false && $allFields[$field]['table'] != 'other' &&
            $allFields[$field]['table'] != 'static') {
            //process with alias
            $alias = (isset($allFields[$field]['alias']) &&
            $allFields[$field]['alias'] ? $allFields[$field]['alias'] . '.' : '');

            if (isset($allFields[$field]['as']) && $allFields[$field]['as']) {
                $specific_settings['sqlfields'][] = $alias . '`' . Tools::substr(
                    strstr($allFields[$field]['field'], '_'),
                    Tools::strlen('_')
                ) . '` as ' . $allFields[$field]['field'] . '';
            } else {
                $specific_settings['sqlfields'][] = $alias . '`' . $allFields[$field]['field'] . '`';
            }
        }
    }

    /**
     * @param array $allFields
     * @param $field
     * @param $name
     * @param array $specific_settings
     */
    public function getAllFieldsAndLabels(array $allFields, $field, $name, array &$specific_settings)
    {
        $specific_settings['allexportfields'][] = $allFields[$field]['field'];
        $specific_settings['labels'][] = $name[0];
    }

    public function saveProgressToFile($current_row, $clean = false)
    {
        $response = array(
            'total' => ($clean ? 1 : (int)$this->rowsNumber),
            'current' => ($clean ? 0 : (int)$current_row),
        );

        $file = dirname(__FILE__) . '/progress.txt';
        file_put_contents($file, json_encode($response));
    }

    public function writeToFile($ae, $sorted_fields, $elements, $entityExportObject)
    {
        $this->rowsNumber = $entityExportObject->rowsNumber;

//        if(!$this->rowsNumber) {
//            return '';
//        }

        $url = null;
        $file = null;
        $style = $this->getHeaderStyle();

        if (!$this->isOrderPerFileEnable($sorted_fields, $ae)) {
            $url = $this->getFileUrl($ae->filename, $ae->type, $ae->file_format);
            $file = $this->openFileAndWriteHeader($url, $ae, $sorted_fields, $style);
        }

        $i = 1;
        $previous_id_order = 0;
        while ($element = $this->nextRow($elements)) {
            if ($i == $this->rowsNumber) {
                $this->lastElement = $element;
            }

            if ($this->isOrderPerFileEnable($sorted_fields, $ae) && $previous_id_order !== 0
                && $previous_id_order !== (int)$element['id_order']) {
                $this->closeFile($file, $ae->file_format);
                $file = null;
            }

            if ($this->isOrderPerFileEnable($sorted_fields, $ae) && empty($file)) {
                $isUrlExists = isset($url[$element['id_order']]);
                if (!$isUrlExists) {
                    $url[$element['id_order']] = $this->getFileUrl(
                        ($ae->filename ? $ae->filename : 'orders') . '_' . $element['id_order'],
                        $ae->type,
                        $ae->file_format
                    );
                }
                $file = $this->openFileAndWriteHeader(
                    $url[$element['id_order']],
                    $ae,
                    $sorted_fields,
                    $style,
                    $isUrlExists
                );
            }

            $this->getDataObjectFromAndStaticFields($element, $file, $sorted_fields, $ae, $entityExportObject);
            $this->saveProgressToFile($i);

            if ($this->isOrderPerFileEnable($sorted_fields, $ae) && $previous_id_order !== 0) {
                $previous_id_order = (int)$element['id_order'];
            }
            ++$i; //progress bar
        }

        $this->closeFile($file, $ae->file_format);

        return $url;
    }

    public function getHeaderStyle()
    {
        return (new Box\Spout\Writer\Style\StyleBuilder())->setfontbold()
            ->setfontcolor(Box\Spout\Writer\Style\Color::BLACK)
            ->setShouldWrapText()
            ->setBackgroundColor(Box\Spout\Writer\Style\Color::rgb(198, 240, 202))
            ->build();
    }

    public function getRowStyle()
    {
        $style = (new Box\Spout\Writer\Style\StyleBuilder())
            ->setFontColor(Box\Spout\Writer\Style\Color::BLACK)
            ->setShouldWrapText()
            ->setBackgroundColor(
                ($this->currentColor == 'white' ? Box\Spout\Writer\Style\Color::rgb(219, 240, 255) :
                    Box\Spout\Writer\Style\Color::WHITE)
            )->build();
        $this->currentColor = ($this->currentColor == 'white' ? 'blue' : 'white');
        return $style;
    }

    public function closeFile($file, $file_format)
    {
        if ($file_format === 'csv') {
            fclose($file);
        } else {
            $file->close();
        }
    }

    public function getFileUrl($filename, $type, $file_format)
    {
        //open file for write
        if ($filename == null || $filename == '') {
            $filename = $type . date('Y-m-d_His');
        } else {
            $filename = $filename;
        }

        if ($file_format === 'csv') {
            $filename = $filename . '.csv';
        } else {
            $filename = $filename . '.' . $file_format;
        }

        $url = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/' . $type;
        if (!is_dir($url)) {
            mkdir($url);
        }

        return $url . '/' . $filename;
    }

    public function openFileAndWriteHeader($url, $ae, $sorted_fields, $style, $isUrlExists = null)
    {
        if ($ae->file_format === 'csv') {
            $file = @fopen($url, ($isUrlExists ? 'a' : 'w'));
            //add labels for export data
            if ($ae->add_header) {
                $this->fileWrite($ae, $sorted_fields, $file);
            }
        } else {
            $file = $this->getSpoutWriter($ae->file_format);
            $file->openToFile($url);

            if ($ae->add_header) {
                $file->addRowWithStyle($sorted_fields['labels'], $style);
            }
        }
        return $file;
    }

    public function getSpoutWriter($file_format)
    {
        $format = array('xlsx', 'ods', 'csv');
        if (!in_array($file_format, $format)) {
            return false;
        }
        //$file_extension = strtoupper($file_format);
        return Box\Spout\Writer\WriterFactory::create($file_format);
    }

    public function displayErrors($errors)
    {
        if (count($errors)) {
            foreach ($errors as $error) {
                echo $error . ' ';
            }
        }
    }

    public function saveLastExportId($ae, $myLastElement)
    {
        if ($ae->only_new && isset($myLastElement[$this->getId($ae->type)])) {
            $ae->last_exported_id = $myLastElement[$this->getId($ae->type)];
            $ae->save();
        }

        return $ae;
    }

    public function processFile($ae, $url)
    {
        if ($ae->save_type == 0) {
            return true;
        };

        if ($ae->save_type == 2) {
            return $this->sentFile($url, $ae->email, $ae->filename, $ae->name);
        }

        if ($ae->save_type == 1 or $ae->save_type == 3) {
            $protocol = Tools::strtoupper(SaveType::getSaveTypeNameById($ae->save_type));
            $ftp = new $protocol($ae->ftp_hostname, $ae->ftp_user_name, $ae->ftp_user_pass);
            $this->ftpFile($ftp, $url, $ae->ftp_directory);
        }
    }

    public function ftpFile($protocol, $export_file, $directory)
    {
        if ($protocol->getErrors()) {
            $this->displayErrors($protocol->getErrors());
            return false;
        }
        if ($directory != null && $directory != "") {
            $protocol->changeDir($directory);
        }

        $filename = basename($export_file);
        $protocol->put($filename, $export_file);

        if ($protocol->getErrors()) {
            $this->displayErrors($protocol->getErrors());
            return false;
        }
    }

    /**
     * @param $ae
     * @param $sorted_fields
     * @param $file
     */
    public function fileWrite($ae, $sorted_fields, $file)
    {
        fwrite($file, implode($ae->delimiter, $sorted_fields['labels']) . "\r\n");
    }

    public function nextRow($elements)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->nextRow($elements);
    }

    public function getDataObjectFromAndStaticFields($element, $file, $sorted_fields, $ae, $entityExportObject)
    {
        $id_object = $element[$this->getId($ae->type)];
        //instance od product object
        $obj = $this->getObject($id_object, $ae->type);
        //has attributes clean varible
        $this->hasAttr = 0;
        if (isset($sorted_fields['otherfields'])) {
            foreach ($sorted_fields['otherfields'] as $key => $value) {
                //convert string to camel case
                //to meet prestashop validation rools
                $run = $this->toCamelCase($ae->type . '_' . $value['field']);
                if ($value['isCustom']) {
                    $element[$value['field']] = CustomFields::$run($obj, $ae, $element);
                } else {
                    $element[$value['field']] = $entityExportObject->$run($obj, $ae, $element);
                }
            }
        }

        //add static fields
        if (isset($sorted_fields['static'])) {
            foreach ($sorted_fields['static'] as $key => $value) {
                $element[$key] = $value;
            }
        }

        if ($ae->type == 'products' && isset($sorted_fields['attribute_fields'])) {
            $element = $this->processWithAttributes($obj, $element, $file, $sorted_fields, $ae, $entityExportObject);
        }

        if ($this->hasAttr == 0) {
            $this->fputToFile($file, $sorted_fields['allexportfields'], $element, $ae);
        }

        return $element;
    }

    public function getId($type)
    {
        $object = ExportEnum::getObjectByEntityName($type);

        return 'id' . ($object ? '_' . $object : '');
    }

    public function getObject($id_object, $entity, $full = false)
    {
        $object = ExportEnum::getObjectByEntityName($entity);

        if ($object === 'products') {
            return new Product($id_object, $full);
        } elseif ($object && $object !== 'products') {
            $object = Tools::ucfirst($object);
            return new $object($id_object);
        }

        return '';
    }

    /**
     * Translates a string with underscores
     * into camel case (e.g. first_name -> firstName)
     *
     * @param string $str String in underscore format
     * @param bool $capitalise_first_char If true, capitalise the first char in $str
     * @return string $str translated into camel caps
     */
    public function toCamelCase($str, $capitalise_first_char = false)
    {
        if ($capitalise_first_char) {
            $str[0] = Tools::strtoupper($str[0]);
        }
        $func = function ($c) {
            return Tools::strtoupper($c[1]);
        };
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    public function processWithAttributes($obj, $element, $file, $sorted_fields, $ae, $entityExportObject)
    {
        $combArray = null;
        $combArray = $this->getProductCombination($obj, $ae);
        $elementCopy = null;
        if (isset($combArray)) {
            $this->hasAttr = 1;
            foreach ($combArray as $products_attribute) {
                $elementCopy = null;
                $elementCopy = $element;
                foreach ($sorted_fields['attribute_fields'] as $value) {
                    $run = $this->toCamelCase($value);
                    $elementCopy[$value] = $entityExportObject->$run($obj, $products_attribute, $ae);
                }
                $this->fputToFile($file, $sorted_fields['allexportfields'], $elementCopy, $ae);
            }
        } else {
            // add empty array keys for products which don't have attributes
            foreach ($sorted_fields['attribute_fields'] as $value) {
                $element[$value] = '';
            }
        }

        return $element;
    }

    public function getProductCombination($obj, $ae)
    {
        $combArray = null;
        $groups = null;

        $combinaisons = $obj->getAttributeCombinations((int)($ae->id_lang));
        if (is_array($combinaisons)) {
            $combinationImages = $obj->getCombinationImages((int)($ae->id_lang));

            foreach ($combinaisons as $combinaison) {
                $combArray[$combinaison['id_product_attribute']]['id_product_attribute'] =
                    $combinaison['id_product_attribute'];
                $combArray[$combinaison['id_product_attribute']]['wholesale_price'] = $combinaison['wholesale_price'];
                $combArray[$combinaison['id_product_attribute']]['price'] = $combinaison['price'];
                $combArray[$combinaison['id_product_attribute']]['weight'] = $combinaison['weight'];
                $combArray[$combinaison['id_product_attribute']]['unit_impact'] = $combinaison['unit_price_impact'];
                $combArray[$combinaison['id_product_attribute']]['reference'] = $combinaison['reference'];
                $combArray[$combinaison['id_product_attribute']]['supplier_reference'] =
                    $combinaison['supplier_reference'];
                $combArray[$combinaison['id_product_attribute']]['ean13'] = $combinaison['ean13'];
                $combArray[$combinaison['id_product_attribute']]['upc'] = $combinaison['upc'];
                $combArray[$combinaison['id_product_attribute']]['minimal_quantity'] = $combinaison['minimal_quantity'];
                $combArray[$combinaison['id_product_attribute']]['location'] = $combinaison['location'];
                $combArray[$combinaison['id_product_attribute']]['quantity'] = $combinaison['quantity'];
                $combArray[$combinaison['id_product_attribute']]['id_image'] =
                    isset($combinationImages[$combinaison['id_product_attribute']][0]['id_image']) ?
                        $combinationImages[$combinaison['id_product_attribute']][0]['id_image'] : 0;
                $combArray[$combinaison['id_product_attribute']]['images'] =
                    isset($combinationImages[$combinaison['id_product_attribute']]) ?
                        $combinationImages[$combinaison['id_product_attribute']] : '';
                $combArray[$combinaison['id_product_attribute']]['default_on'] = $combinaison['default_on'];
                $combArray[$combinaison['id_product_attribute']]['ecotax'] = $combinaison['ecotax'];
                $combArray[$combinaison['id_product_attribute']]['id_product_attribute'] =
                    $combinaison['id_product_attribute'];
                $combArray[$combinaison['id_product_attribute']]['attributes'][] =
                    array($combinaison['group_name'], $combinaison['attribute_name'], $combinaison['id_attribute']);
                $combArray[$combinaison['id_product_attribute']]['attributes_name'][] =
                    array($combinaison['group_name'], $combinaison['id_attribute_group']);
                $combArray[$combinaison['id_product_attribute']]['attributes_value'][] =
                    array($combinaison['attribute_name'], $combinaison['id_attribute']);
                if ($combinaison['is_color_group']) {
                    $groups[$combinaison['id_attribute_group']] = $combinaison['group_name'];
                }
                // 4.10.2019
                $combArray[$combinaison['id_product_attribute']]['available_date'] = $combinaison['available_date'];
                if (_PS_VERSION_ >= 1.7) {
                    $combArray[$combinaison['id_product_attribute']]['low_stock_threshold'] =
                        $combinaison['low_stock_threshold'];
                    $combArray[$combinaison['id_product_attribute']]['low_stock_alert'] =
                        $combinaison['low_stock_alert'];
                }
                if (isset($combinaison['mpn'])) {
                    $combArray[$combinaison['id_product_attribute']]['mpn'] = $combinaison['mpn'];
                }
            }
        }

        return $combArray;
    }

    public function fputToFile($file, $allexportfields, $object, $ae)
    {
        if (!$file || !$allexportfields || !$object || !$ae) {
            throw new PrestaShopException('Invalid argument');
        }

        $readyForExport = $this->preperFieldsForExport($allexportfields, $object, $ae);

        if ($ae->file_format === 'csv') {
            //write into csv line by line
            if ($ae->separator == '') {
                fputs($file, implode($readyForExport, $ae->delimiter) . "\n");
            } else {
                $this->fputcsvEol($file, $readyForExport, $ae->delimiter, $ae->separator, "\r\n");
            }
        } else {
            $file->addRowWithStyle($readyForExport, $this->getRowStyle());
        }
    }

    public function fputcsvEol($handle, $array, $delimiter = ',', $enclosure = '"', $eol = "\n")
    {
        $return = fputcsv($handle, $array, $delimiter, $enclosure);
        if ($return !== FALSE && "\n" != $eol && 0 === fseek($handle, -1, SEEK_CUR)) {
            fwrite($handle, $eol);
        }
        return $return;
    }

    /**
     * @param $allexportfields
     * @param $object
     * @param $ae
     * @return array
     */
    public function preperFieldsForExport($allexportfields, $object, $ae)
    {
        $readyForExport = array();
        //put in correct sort order
        foreach ($allexportfields as $value) {
            if (!Tools::substr($object[$value], 0, 1) == '0') {
                $this->processDecimalSettings($object, $ae, $value);
                $this->castValues($object, $value);
            }
            $readyForExport[$value] = ($ae->file_format === 'csv' ?
                iconv('UTF-8', $ae->charset, $object[$value]) : $object[$value]);
        }
        return $readyForExport;
    }


    public function isOrderPerFileEnable($sorted_fields, $ae)
    {
        return (isset($sorted_fields['orderPerFile']) && $sorted_fields['orderPerFile'] &&
        $ae->save_type == 0 ? true : false);
    }

    /**
     * @param $object
     * @param $ae
     * @param $value
     *
     * @return mixed
     */
    public function processDecimalSettings(&$object, $ae, $value)
    {
        if ($this->isDecimal($object[$value])) {
            $this->roundValues($object, $ae, $value);
            $this->replaceDecimalSeparator($object, $ae, $value);
        }
        if ($ae->strip_tags) {
            $object[$value] = strip_tags($object[$value]);
        }
    }

    /**
     * @param $object
     * @param $value
     */
    public function castValues(&$object, $value)
    {
        if (Validate::isInt($object[$value])) {
            $object[$value] = (int)$object[$value];
            return; // important to return early because it will add 0 to int
        }

        if (Validate::isFloat($object[$value])) {
            $object[$value] = (float)$object[$value];
        }
    }

    /**
     * @param $object
     * @param $ae
     * @param $value
     */
    public function roundValues(&$object, $ae, $value)
    {
        if ((int)$ae->decimal_round !== -1) {
            $object[$value] = Tools::ps_round((float)$object[$value], $ae->decimal_round);
        }
    }

    /**
     * @param $object
     * @param $ae
     * @param $value
     * @return mixed
     */
    public function replaceDecimalSeparator(&$object, $ae, $value)
    {
        if ((int)$ae->decimal_separator !== -1) {
            $object[$value] = str_replace(',', $ae->decimal_separator, $object[$value]);
            $object[$value] = str_replace('.', $ae->decimal_separator, $object[$value]);
        }
    }

    public function isDecimal($val)
    {
        return is_numeric($val) && strpos($val, '.') !== false;
    }

    public function sentFile($export_file, $email, $filename, $name)
    {
        if (!$filename) {
            $filename = pathinfo($export_file, PATHINFO_FILENAME);
        }

        $extension = pathinfo($export_file, PATHINFO_EXTENSION);
        $file_attachment = null;
        $file_attachment['content'] = Tools::file_get_contents($export_file);
        $file_attachment['name'] = $filename . '.' . $extension;
        $file_attachment['mime'] = $this->mime_attachment[$extension];

        $id_lang = Configuration::getGlobalValue("PS_LANG_DEFAULT");
        $emails = array_map('trim', explode(',', $email));

        foreach ($emails as $to) {
            if (!Mail::Send(
                $id_lang,
                'index',
                $name,
                null,
                $to,
                null,
                null,
                "advanced export",
                $file_attachment,
                null,
                dirname(__FILE__) . '/../../mails/'
            )) {
                throw new PrestaShopException("Can't sent email to " . $to);
            }
        }

        return true;
    }

    /**
     * @param $ae
     * @param $fields
     * @return mixed
     */
    private function getEntityExportObject($ae, $fields)
    {
        $class_name = Tools::ucfirst($ae->type) . 'Export';
        require_once $class_name . '.php';
        $entity_export = new $class_name($ae, $fields);

        return $entity_export;
    }
}
