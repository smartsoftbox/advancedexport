<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../classes/ModuleTools.php';

define('_AE_CSV_PATH_', _PS_ROOT_DIR_ . '/modules/advancedexport/csv/');

class AdminAdvancedExportBaseController extends ModuleAdminController
{
    public $moduleTools;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleTools(new ModuleTools());
    }

    public function setModuleTools($moduleTools)
    {
        $this->moduleTools = $moduleTools;
    }

    /**
     * @param string $string
     * @param null $class
     * @param bool $addslashes
     * @param bool $htmlentities
     * @return string
     */
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if (_PS_VERSION_ >= '1.7') {
            return Context::getContext()->getTranslator()->trans($string);
        } else {
            return parent::l($string, $class, $addslashes, $htmlentities);
        }
    }

    public function getLabelsAsFieldsArray($labels, $mapping)
    {
        $fields_value = array();
        if (!empty($labels)) {
            foreach (array_keys($labels) as $key) {
                $field_name = 'fields[' . $key . ']';
                if ($field_value = $this->moduleTools->getValue($field_name)) {
                    $fields_value[$field_name] = $field_value;
                } elseif (isset($mapping) && isset($mapping[$key])) {
                    $fields_value[$field_name] = $mapping[$key];
                } else {
                    $fields_value[$field_name] = '';
                }
            }
        }
        return $fields_value;
    }

    /**
     * @param $params
     * @return string
     */
    public function getProtocol($params)
    {
        if (isset($params['export']) && $params['export'] == true) {
            $protocol = ($params['save_type'] == 1 ? 'FTP' : 'SFTP');
        } else {
            $protocol = ($params['import_from'] == 3 ? 'FTP' : 'SFTP');
        }
        return $protocol;
    }

    public function addJavaScriptVariable()
    {
        $this->context->smarty->assign(array(
            'base' => $this->context->link->getAdminLink('AdminModules', false),
            'token' => Tools::getAdminTokenLite('AdminModules')
        ));

        return $this->context->smarty->fetch(
            dirname(__FILE__) . '/../../views/templates/admin/variable.tpl'
        );
    }

    public function getEntity()
    {
        if ($entity = $this->moduleTools->getValue('type')) {
            return $entity;
        }

//        if (isset($cookie->current_tab_id) && $cookie->current_tab_id) {
//            return $cookie->current_tab_id; // default if no cookie
//        }

        return 'products'; // default if no cookie
    }

    /**
     * @param $id
     * @return AdvancedExportClass
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getAdvancedExportClass($id)
    {
        $ae = new AdvancedExportClass($id);
        if (is_object($ae)) {
            return $ae;
        }

        throw new PrestaShopException('Invalid AdvancedExport object.');
    }


    /**
     * @return bool
     */
    public function isGreaterOrEqualThenPrestaShopVersion($version)
    {
        return (_PS_VERSION_ >= $version ? true : false);
    }

    public function addToGet($get_name, $get_value)
    {
        $get_parameter = array();
        $get_parameter[1] = $get_name . '=' . $get_value;

        $this->addToGETQuery($get_parameter);

        return $this->moduleTools->getValue($get_name);
    }

    public function addToGETQuery($query)
    {
        Tools::argvToGET(2, $query);
    }

    public static function getFileFromFtp(
        $protocol,
        $ftp_hostname,
        $ftp_user_name,
        $ftp_user_pass,
        $target,
        $local = null,
        $ftp_directory = null
    ) {
        if (!in_array(Tools::strtolower($protocol), array('ftp', 'sftp'))) {
            throw new PrestaShopException('Invalid Protocol');
        }

        $ftp = new $protocol($ftp_hostname, $ftp_user_name, $ftp_user_pass);

        self::checkFtpErrors($ftp);

        if ($ftp_directory != null && $ftp_directory != "") {
            $ftp->changeDir($ftp_directory);
        }

        self::checkFtpErrors($ftp);

        $ftp->get($target, ($local ? $local : $target));

        self::checkFtpErrors($ftp);

        return true;
    }


    public function checkConnection()
    {
        $params = $this->moduleTools->getValue('params');
        $protocol = $this->getProtocol($params);

        if (!$params['hostname'] || !$params['username'] || !$params['password']) {
            echo json_encode(array('Empty Fields'));
            exit();
        }

        $ftp = new $protocol(
            (string)$params['hostname'],
            (string)$params['username'],
            (string)$params['password'],
            ($params['port'] ? (int)$params['port'] : false)
        );

        $errors = $ftp->getErrors();

        if (!$errors && $params['path']) {
            $ftp->changeDir($params['path']);
        }

        $errors = $ftp->getErrors();

        if (!$errors && isset($params['import_from'])) {
            $ftp->isFileExists($params['filename']);
        }

        $errors = $ftp->getErrors();

        return  json_encode($errors);
    }

    public static function checkFtpErrors($ftp)
    {
        if ($ftp->getErrors()) {
            return $ftp->getErrors();
        }
    }

    public function getImportFilePath($id)
    {
        return _AE_IMPORT_PATH_ . $id;
    }

    public function getFilters()
    {
        $filtersNames = $this->getFiltersNames($this->getEntity());
        $parameters = '';
        $cookie = Context::getContext()->cookie; // I added this on validation
        foreach ($filtersNames as $filtersName) {
            if ($filterExport = $this->moduleTools->getValue($filtersName)) {
                $this->context->cookie->{$filtersName} = $filterExport;
                $parameters .= '&' . $filtersName . '=' . $filterExport;
            } elseif (isset($cookie->{$filtersName})) {
                $parameters .= '&' . $filtersName . '=' . $cookie->{$filtersName};
            }
        }

        return $parameters;
    }

    public function formatSize($size)
    {
        $sizes = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
        if ($size == 0) {
            return 'n/a';
        } else {
            return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i];
        }
    }

    public function getFile($path)
    {
        $dir = (string)realpath($path);
        if (file_exists($dir)) {
            $ext = pathinfo($dir, PATHINFO_EXTENSION);

            switch ($ext) {
                case 'csv':
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/csv');
                    header('Content-Disposition: attachment; filename=' . basename($dir));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($dir));
                    break;
                case 'xlsx':
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename=' . basename($dir));
                    header('Cache-Control: max-age=0');
                    break;
                case 'ods':
                    header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
                    header('Content-Disposition: attachment;filename=' . basename($dir));
                    header('Cache-Control: max-age=0');
                    break;
            }

            //ob_clean();
            flush();
            readfile($dir);
            exit;
        }
    }

//    public function processFilter()
//    {
//        parent::processFilter();
//
//        if (Tools::getValue('submitFilter' . $this->list_id) || Tools::getValue($this->list_id . 'Orderby') ||
//            Tools::getValue($this->list_id . 'Orderway') ||
//            Tools::getValue($this->list_id . '_pagination')) {
//            $this->redirect_after = Context::getContext()->link->getAdminLink(
//                    _ADMIN_AE_,
//                    true
//                ) . $this->getFilters();
//        }
//    }
//
//    public function processResetFilters($list_id = null)
//    {
//        parent::processResetFilters($list_id); // TODO: Change the autogenerated stub
//
//        $this->redirect_after = Context::getContext()->link->getAdminLink(
//                _ADMIN_AE_,
//                true
//            ) . $this->getFilters();
//    }

    public function getFilesFromDirectory($dirname, $formats)
    {
        if (!is_dir($dirname)) {
            throw new PrestaShopException($this->l('Invalid Directory.'));
        }

        return array_map(
            'basename',
            glob($dirname . '*.{' . implode(',', $formats) . '}', GLOB_BRACE)
        );
    }

    /**
     * @return array
     */
    public function getFiltersNames($type)
    {
        $filter_name = array();
        $filter_name[] = 'submitFilter' . $type . 'export';
        $filter_name[] = 'submitFilter' . $type . 'files';
        $filter_name[] = $type . 'export_pagination';
        $filter_name[] = $type . 'files_pagination';
        $filter_name[] = 'submitFilterimportfiles';
        $filter_name[] = 'submitFilteradvancedexportcron';
        $filter_name[] = 'advancedexportimport_pagination';
        $filter_name[] = 'importfiles_pagination';
        $filter_name[] = 'advancedexportcron_pagination';
        $filter_name[] = 'submitFilteradvancedexportimport';

        return $filter_name;
    }
}
