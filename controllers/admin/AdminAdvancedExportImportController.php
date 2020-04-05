<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportImportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';

require_once dirname(__FILE__) . '/AdminAdvancedExportImportFileController.php';
require_once dirname(__FILE__) . '/../../classes/Data/ImportEnum.php';
require_once dirname(__FILE__) . '/../../classes/Data/ImportFrom.php';
require_once dirname(__FILE__) . '/AdminAdvancedExportPrestaImportController.php';
require_once dirname(__FILE__) . '/AdminAdvancedExportBaseController.php';

if (_PS_VERSION_ < 1.7) {
    exit;
}

define('_AE_IMPORT_PATH_', _PS_ROOT_DIR_ . '/modules/advancedexport/csv/import/');

class AdminAdvancedExportImportController extends AdminAdvancedExportBaseController
{
    const DEFAULT_SEPARATOR = ',';
    const DEFAULT_MULTI_VALUE_SEPARATOR = ';';
    const IMPORT_ALLOWED_FILE_FORMAT = array('csv', 'xls', 'xlsx', 'xlst', 'ods', 'ots');

    public function __construct()
    {
        $this->table = 'advancedexportimport';
        $this->className = 'AdvancedExportImportClass';
        $this->fields_list = array(
            'id_advancedexportimport' => array(
                'title' => $this->l('ID'),
                'width' => 50,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 100,
            ),
            'import_from' => array(
                'title' => $this->l('Import From'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
            ),
            'export_model' => array(
                'title' => $this->l('Export Model'),
                'width' => 100,
                'orderby' => false,
                'search' => false,
            ),
        );

        $this->context = Context::getContext();
        $this->context->controller = $this;

        $this->bootstrap = true;

        $this->addRowAction('edit');
        $this->addRowAction('import');
        $this->addRowAction('files');
        $this->addRowAction('delete');

        parent::processFilter();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Would you like to delete the selected items?'),
            )
        );

        parent::__construct();
    }

    public function renderForm()
    {
        if (!($aeImport = $this->loadObject(true))) {
            return;
        }

        $this->fields_form = $this->getFormFields();

        $this->getSeparator($aeImport);
        $this->getMultiValueSeparator($aeImport);
        $this->getTestConnection();

        return parent::renderForm();
    }

    public function getFormFields()
    {
        $this->switch = ($this->isGreaterOrEqualThenPrestaShopVersion('1.6') ? 'switch' : 'radio');

        $result = array(
            'legend' => array(
                'title' => $this->l('Settings Form'),
                'icon' => 'icon-envelope',
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_advancedexportimport',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                    'desc' => $this->l('Import internal name'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('What do you want to import?'),
                    'name' => 'entity',
                    'default' => '-1',
                    'class' => '',
                    'options' => array(
                        'default' => array(
                            'label' => $this->l('Please select entity'),
                            'value' => '-1',
                        ),
                        'query' => $this->getExportEntities(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Import From'),
                    'name' => 'import_from',
                    'default' => '-1',
                    'id' => 'import_from',
                    'options' => array(
                        'query' => ImportFrom::getImportFrom(),
                        'id' => 'id',
                        'name' => 'public_name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select export model.'),
                    'name' => 'id_advancedexport',
                    'default' => '0',
                    'class' => 'import_from_model',
                    'options' => array(
                        'default' => array(
                            'label' => $this->l('Please select export model'),
                            'value' => '0',
                        ),
                        'query' => AdvancedExportClass::getAll(),
                        'id' => 'id_advancedexport',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Language of the file'),
                    'name' => 'iso_lang',
                    'default' => '0',
                    'class' => 'custom-hide',
                    'options' => array(
                        'query' => Language::getLanguages(true),
                        'id' => 'iso_code',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Url'),
                    'name' => 'url',
                    'class' => 'import_from_url',
                    'required' => true,
                    'desc' => $this->l('Please enter import url.'),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('File'),
                    'id' => 'upload_file',
                    'name' => 'upload_file',
                    'class' => 'import_from_file',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Filename'),
                    'name' => 'filename',
                    'class' => 'import_from_ftp input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Hostname'),
                    'name' => 'ftp_hostname',
                    'class' => 'import_from_ftp input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Port'),
                    'placeholder' => 21,
                    'name' => 'ftp_port',
                    'class' => 'import_from_ftp input fixed-width-xs other-border',
                    'desc' => $this->l('Leave blank then default will be used (ftp:21 / sftp:22).'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Username'),
                    'name' => 'ftp_user_name',
                    'class' => 'import_from_ftp input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Password'),
                    'name' => 'ftp_user_pass',
                    'class' => 'import_from_ftp input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Path'),
                    'name' => 'ftp_directory',
                    'class' => 'import_from_ftp other-border',
                ),
                array(
                    'type' => 'free',
                    'name' => 'test_connection',
                    'class' => 'import_from_ftp'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Field separator'),
                    'name' => 'separator',
                    'class' => 'custom-hide fixed-width-xs',
                    'required' => true,
                    'defaultValue' => '0',
                    'maxlength' => 1,
                    'desc' => $this->l('e.g. 1; Blouse; 129.90; 5'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Multiple value separator'),
                    'name' => 'multi_value_separator',
                    'class' => 'custom-hide fixed-width-xs',
                    'required' => true,
                    'maxlength' => 1,
                    'desc' => $this->l('e.g. Blouse; red.jpg, blue.jpg, green.jpg; 129.90'),
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Delete all categories before import '),
                    'name' => 'truncate',
                    'class' => 't hide',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Use product reference as key'),
                    'name' => 'match_ref',
                    'class' => 't hide',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Skip thumbnails regeneration'),
                    'name' => 'regenerate',
                    'class' => 't hide',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Force all ID numbers'),
                    'name' => 'forceIDs',
                    'class' => 't hide',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Send notification email'),
                    'name' => 'send_email',
                    'class' => 't hide',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return $result;
    }

    /**
     * @param ObjectModel $aeImport
     */
    public function getSeparator(ObjectModel $aeImport)
    {
        $this->fields_value['separator'] = isset($aeImport->separator) && !empty($aeImport->separator) ?
            $aeImport->separator : self::DEFAULT_SEPARATOR;
    }

    /**
     * @param ObjectModel $aeImport
     */
    public function getMultiValueSeparator(ObjectModel $aeImport)
    {
        $this->fields_value['multi_value_separator'] = isset($aeImport->multi_value_separator) &&
        !empty($aeImport->multi_value_separator) ?
            $aeImport->multi_value_separator : self::DEFAULT_MULTI_VALUE_SEPARATOR;
    }

    public function getTestConnection()
    {
        $this->fields_value['test_connection'] = $this->moduleTools->fetch(_PS_MODULE_DIR_ .
            'advancedexport/views/templates/admin/test_connection_button.tpl');
    }

    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList((int)$id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        if (count($this->_list)) {
            $this->addListColumns($this->_list);
        }
    }


    public function displayImportLink($token, $id)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_import.tpl');

        $tpl->assign(array(
            'href' => self::$currentIndex . '&token=' . $token . '&id_advancedexportimport=' . $id .
                '&import' . $this->table . '=1',
            'action' => $this->l('Import'),
            'id' => $id,
            'is_presta_16' => $this->isGreaterOrEqualThenPrestaShopVersion('1.6'),
        ));

        return $tpl->fetch();
    }

    public function displayFilesLink($token, $id)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_files.tpl');

        $file_controller = Context::getContext()->link->getAdminLink(
            _ADMIN_AE_IMPORT_FILE_,
            true
        );

        $tpl->assign(array(
            'href' =>  $file_controller . '&id_advancedexportimport=' . $id . '&files' . $this->table . '=1',
            'action' => $this->l('Files'),
            'id' => $id,
            'is_presta_16' => $this->isGreaterOrEqualThenPrestaShopVersion('1.6'),
        ));

        return $tpl->fetch();
    }


    public function displayAjaxGetExportForm()
    {
        $import_models = $this->renderList();
        $aeImportFile = new AdminAdvancedExportImportFileController();

        $id_advancedexportimport = $this->context->cookie->{'id_advancedexportimport'};

        $files = null;
        if ($id_advancedexportimport) {
            $files = $aeImportFile->renderList();
        }

        echo $import_models . $files;
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (isset($this->toolbar_btn['back'])) {
            $this->toolbar_btn['back'] = array(
                'href' => $this->moduleTools->getAdminLink(_ADMIN_AE_),
                'desc' => $this->l('Back to list.'),
            );
        }
    }

    public function processDelete()
    {
        $res = parent::processDelete();
        Tools::deleteDirectory(_AE_IMPORT_PATH_ . $this->moduleTools->getValue('id_advancedexportimport'));
        $this->redirect_after = $this->moduleTools->getAdminLink(_ADMIN_AE_, true) . '&conf=1';

        return $res;
    }

    public function processBulkDelete()
    {
        $res = parent::processBulkDelete();
        $imports = $this->moduleTools->getValue('advancedexportimportBox');
        foreach ($imports as $id) {
            if (file_exists(_AE_IMPORT_PATH_ . $id)) {
                Tools::deleteDirectory(_AE_IMPORT_PATH_ . $id);
            }
        }
        $this->redirect_after = $this->moduleTools->getAdminLink(_ADMIN_AE_, true) . '&conf=1';

        return $res;
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $importEnum = new ImportEnum();
        $this->content .= '<script type="text/javascript">
            var ae_controller_model_url = "' . $this->context->link->getAdminLink(_ADMIN_AE_MODEL_) . '";
            var ae_controller_model = "' . _ADMIN_AE_MODEL_ . '";
            var importEntities = ' . json_encode($importEnum->getFields()) . ';
        </script>';

        $this->content .= $this->addJavaScriptVariable();
        $path = __PS_BASE_URI__ . 'modules/advancedexport/';
        $this->context->controller->addCSS($path . 'views/css/admin.css');
        $this->context->controller->addJS($path . 'views/js/advanced_export_import/form.js');
        $this->context->controller->addJS($path . 'views/js/test_connection.js');
    }

    private function getExportEntities()
    {
        $importFields = new ImportEnum();
        $entities = $importFields->getFields();
        $result = array_map(function ($entity) {
            return array('name' => $entity['entity'], 'id' => $entity['id']);
        }, $entities);

        return $result;
    }

    public function processFilter()
    {
        if (Tools::getValue('submitFilter' . $this->list_id) || Tools::getValue($this->list_id . 'Orderby') ||
            Tools::getValue($this->list_id . 'Orderway') ||
            Tools::getValue($this->list_id . '_pagination')) {
            $this->redirect_after = Context::getContext()->link->getAdminLink(
                _ADMIN_AE_,
                true
            ) . $this->getFilters();
        }
    }

    public function processResetFilters($list_id = null)
    {
        parent::processResetFilters($list_id);
        $this->redirect_after = Context::getContext()->link->getAdminLink(_ADMIN_AE_, true);
    }

    public function initFormMapping($labels, $aeImport)
    {
        $this->fields_form = $this->getMappingForm($labels);

        $back = Tools::safeOutput($this->moduleTools->getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex . '&token=' . $this->token;
        }
        if (!Validate::isCleanHtml($back)) {
            die(Tools::displayError());
        }

        $helper = new HelperForm();
        $helper->back_url = $back;
        $helper->currentIndex = self::$currentIndex;
        $helper->token = $this->token;
        $helper->table = $this->table;
        $helper->identifier = $this->identifier;
        $helper->id = $aeImport->id;
        $helper->toolbar_scroll = false;
        $helper->languages = $this->_languages;
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->fields_value = $this->getMappingFormFieldsValues($aeImport, $labels);
        $helper->toolbar_btn = $this->toolbar_btn;
        $helper->title = $this->trans('Add a new feature value', array(), 'Admin.Catalog.Feature');
        $helper->submit_action = "saveMapping";
        $helper->show_cancel_button = true;
        $this->content .= $helper->generateForm($this->fields_form);
    }

    /**
     * @param AdvancedExportImportClass $aeImport
     * @param $labels
     * @return null
     */
    public function getMappingForm($labels)
    {
        $fields_form = null;
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Mapping Form'),
                'icon' => 'icon-envelope',
            ),
            'input' => array(
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $fields_form[0]['form']['input'] = $this->getLabelFields($labels);

        return $fields_form;
    }

    /**
     * @param $labels
     * @param array $available_fields
     * @return array
     */
    public function getLabelFields($labels)
    {
        $column_index = 0;
        $fields_form = array();

        $available_fields = $this->getAvailableFields();

        if (!empty($labels)) {
            foreach ($labels as $label) {
                $fields_form[] = $this->generateSelect($column_index, $label, $available_fields);
                $column_index++;
            }
        }
        return $fields_form;
    }

    /**
     * @return array
     */
    public function getAvailableFields()
    {
        $adminImportController = new AdminImportControllerCore();
        $available_fields = array();

        foreach ($adminImportController->available_fields as $key => $available_field) {
            $available_fields[$key]['name'] = $key;
            $available_fields[$key]['label'] = $available_field['label'];
        }
        return $available_fields;
    }

    private function generateSelect($column_index, $label, $available_fields)
    {
        return array(
            'type' => 'select',
            'label' => $label,
            'name' => 'fields[' . $column_index . ']',
            'default' => '0',
            'options' => array(
                'query' => $available_fields,
                'id' => 'name',
                'name' => 'label',
            ),
        );
    }

    /**
     * @param $list
     * @return void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function addListColumns(&$list)
    {
        foreach ($list as &$export_model) {
            $export_model['import_from'] = ImportFrom::getImportFromPublicName($export_model['import_from']);
            if ($export_model['id_advancedexport']) {
                $ae = $this->getAdvancedExportClass($export_model['id_advancedexport']);
                $export_model['export_model'] = $ae->name;
            }
        };
    }

//    /**
//     * @param AdvancedExportImportClass $aeImport
//     * @throws PrestaShopDatabaseException
//     * @throws PrestaShopException
//     */
//    public function addImportFileName($id)
//    {
//        $aeImport = $this->getAdvancedExportImportClass($id);
//        if ($aeImport->import_from == 0 && $id_advancedexport = $aeImport->id_advancedexport) {
//            $ae = $this->getAdvancedExportClass($id_advancedexport);
//            $aeImport->filename = $ae->filename . '.' . $ae->file_format;
//        }
//
//        return $aeImport;
//    }

    public function getExportFilePath($ae)
    {
        if (is_null($ae) or empty($ae->type) or empty($ae->filename) or empty($ae->file_format)) {
            throw new PrestaShopException('Invalid export model.');
        }

        //get upload file
        return _AE_CSV_PATH_ . $ae->type . '/' . $ae->filename . '.' . $ae->file_format;
    }

    private function getLabels($path)
    {
        $file_name = basename($path);
        $file_format = $this->getFileFormatFromPath($file_name);

        try {
            $reader = Box\Spout\Reader\ReaderFactory::create($file_format);
            if ($file_format == 'csv') {
                $separator = $this->getSeparatorForReader();
                $reader->setFieldDelimiter($separator);
            }
            $reader->open($path);
            $cnt = 0;
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    if ($cnt == 0) {
                        return $row;
                    }
                }
                $cnt++;
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    private function getFileFormatFromPath($file_path)
    {
        $file_format = pathinfo($file_path, PATHINFO_EXTENSION);
        return $file_format;
    }

    public function getSeparatorForReader()
    {
        $separator = $this->moduleTools->getValue('separator');
        if ($this->isImportFromIsExportModelAndIdIsNotEmpty()) {
            $ae = $this->getAdvancedExportClass($this->moduleTools->getValue('id_advancedexport'));
            $separator = $ae->delimiter;
        }

        return $separator;
    }

    public function isImportFromIsExportModelAndIdIsNotEmpty()
    {
        return ((int)$this->moduleTools->getValue('import_from') === ImportFrom::getImportFromIdByName('model') &&
            $this->moduleTools->getValue('id_advancedexport'));
    }

    public function initContent()
    {
        if ($this->display == 'edit' || $this->display == 'add') {
            if (!$this->loadObject(true)) {
                return;
            }
            $this->content .= $this->renderForm();
        } elseif ($this->display == 'mapping') {
            if (!$this->loadObject(true)) {
                return;
            }
            $this->content .= $this->initFormMapping();
        }

        $this->context->smarty->assign(array(
            'content' => $this->content,
        ));
    }

    public function processSave()
    {
        if (Tools::isSubmit('submitAddadvancedexportimport')) {
            $aeImport = $this->getAdvancedExportImportClass($this->moduleTools->getValue('id_advancedexportimport'));
//            if (!$aeImport->id) {
//                Tools::deleteFile($this->getImportFilePathWithFileName(
//                    $aeImport->id,
//                    $aeImport->filename,
//                    true
//                ));
//            }
            $aeImport = $this->saveImportSettings($aeImport);
            $this->createImportFolder($aeImport->id);
            $this->uploadMappingFile($aeImport);
            $path = $this->getImportPath($aeImport, true);
            $labels = null;

            if($this->isPathExists($path)) {
                $labels = $this->getLabels($path);
                if(!count($labels)) {
                    $this->errors[] = $this->l('It looks like empty file.');
                }
            }

            if (!empty($this->errors)) {
                $this->display = 'edit';
            } else {
                // if we have errors, we stay on the form instead of going back to the list
                $this->initFormMapping($labels, $aeImport);
            }
        }
    }

    public function generateFileToken()
    {
        return sha1(microtime());
    }

    public function postProcess()
    {
        parent::postProcess();

        if (Tools::isSubmit('saveMapping')) {
            if ($this->saveImportMapping()) {
                $this->redirect_after = Context::getContext()->link->getAdminLink(
                    _ADMIN_AE_,
                    true
                );
            }
        }

        if (Tools::getValue('conf')) {
            $this->display = 'mapping';
        }
    }

    public function saveImportSettings($aeImport)
    {
        $aeImport->copyFromPost();

        $aeImport->file_token = ($aeImport->file_token ? $this->generateFileToken() : $aeImport->file_token);
        $aeImport->save();

        return $aeImport;
    }

    public function createImportFolder($id)
    {
        if (!file_exists(_AE_IMPORT_PATH_ . $id)) {
            mkdir(_AE_IMPORT_PATH_ . $id, 0755);
        }

        // copy htaccess to import
        $this->moduleTools->copy(
            _AE_IMPORT_PATH_ . '.htaccess',
            _AE_IMPORT_PATH_ . $id . '/.htaccess'
        );
    }

    public function saveImportMapping()
    {
        $aeImport = $this->getAdvancedExportImportClass($this->moduleTools->getValue('id_advancedexportimport'));
        $aeImport->mapping = json_encode($this->moduleTools->getValue('fields'));
        $aeImport->save();

        return true;
    }

    public function getMappingFormFieldsValues($aeImport, $labels)
    {
//        parent::getFieldsValue($aeImport);

        $mapping = null;
        if ($aeImport->mapping) {
            $mapping = json_decode($aeImport->mapping, true);
        }
        $fields_mapping = $this->getLabelsAsFieldsArray($labels, $mapping);

        return $fields_mapping;
    }

    public function getModelFilePath($aeImport, $mapping = false)
    {
        if ($aeImport->id_advancedexport) {
            $ae = $this->getAdvancedExportClass($aeImport->id_advancedexport);
            $target = $this->getExportFilePath($ae);
            $file_name = $ae->filename . '.' . $ae->file_format;

            $local = $this->getImportFilePathWithFileName($aeImport->id, $file_name, $mapping);

            $this->moduleTools->copy($target, $local);

            return $local;
        }
    }

    public function getUrlFilePath($aeImport, $mapping = false)
    {
        $local = $this->getImportFilePathWithFileName($aeImport->id, basename($aeImport->url), $mapping);
        Tools::copy($aeImport->url, $local);
        Tools::chmodr($local, 0644);

        return $local;
    }

    /**
     * @param $aeImport
     */
    public function uploadMappingFile($aeImport)
    {
        if (isset($_FILES['upload_file']['tmp_name'])) {
            $helper = new HelperUploader($_FILES['upload_file']['name']);
            $helper->setPostMaxSize(Tools::getOctets(ini_get('upload_max_filesize')))
                ->setAcceptTypes(self::IMPORT_ALLOWED_FILE_FORMAT)
                ->setSavePath($this->getImportFilePath($aeImport->id))
                ->upload($_FILES['upload_file'], $this->getImportMappingFileName(
                    $_FILES['upload_file']['name']
                ));
        }
    }

    public function saveLastImportFileName($aeImport, $local)
    {
        $aeImport->import_filename = basename($local);
        $aeImport->save();

        return $aeImport;
    }

    private function getUploadFilePath($aeImport, $mapping = false)
    {
        if (isset($_FILES['upload_file']['tmp_name']) && $_FILES['upload_file']['tmp_name']) {
            return $this->getImportFilePathWithFileName($aeImport->id, $_FILES['upload_file']['name'], true);
        } else {
            return $this->getImportFilePath($aeImport->id) . '/'. $aeImport->import_filename;
        }
    }

    private function getFtpFilePath($aeImport, $mapping = false)
    {
        $local = $this->getImportFilePathWithFileName($aeImport->id, $aeImport->filename, $mapping);

        if (!$result = AdminAdvancedExportBaseController::getFileFromFtp(
            ImportFrom::getImportFromName($aeImport->import_from),
            $aeImport->ftp_hostname,
            $aeImport->ftp_user_name,
            $aeImport->ftp_user_pass,
            $aeImport->filename,
            $local,
            $aeImport->ftp_directory
        )) {
            echo $result;
            exit();
        }

        return $local;
    }

    private function getSFtpFilePath($aeImport)
    {
        $this->getFtpFilePath($aeImport);
    }

    private function getImportFilePathWithFileName($id_import, $file_name, $mapping = false)
    {
        $base = $this->getImportFilePath($id_import) . '/';

        if ($mapping) {
            $base .= $this->getImportMappingFileName($file_name);
        } else {
            $base .= self::getImportFileName($file_name);
        }

        return $base;
    }

    public static function getImportFileName($filename)
    {
        return  date('Y-m-d H:i:s', time()) . ' ' . $filename;
    }

    private function getImportMappingFileName($file_name)
    {
        return  'mapping-' . $file_name;
    }

    public function displayAjaxImport()
    {
        $id = $this->moduleTools->getValue('id');
        $aeImport = $this->getAdvancedExportImportClass($id);
        if ($offset = (int)Tools::getValue('offset') === 0 &&
            $validateOnly = (int)Tools::getValue('validateOnly') === 1) {
            $this->getImportPath($aeImport, false);
        }

        $this->addImportGETValues($aeImport->id);

        $offset = (int)$this->moduleTools->getValue('offset');
        $limit = (int)$this->moduleTools->getValue('limit');
        $validateOnly = ((int)$this->moduleTools->getValue('validateOnly') == 1);
        $moreStep = (int)$this->moduleTools->getValue('moreStep');

        $this->runImport($offset, $limit, $validateOnly, $moreStep, $id);
    }

    public function getImportPath($aeImport, $mapping)
    {
        $import_from = ImportFrom::getImportFromName($aeImport->import_from);
        $method = sprintf('get%sFilePath', Tools::ucfirst($import_from));
        $path = $this->$method($aeImport, $mapping);
        $this->saveLastImportFileName($aeImport, $path);
        return $path;
    }

    public function displayAjaxUploadFile()
    {
        $file = array(
            'tmp_name' => $_FILES['upload_file']['tmp_name'][0],
            'name' => $_FILES['upload_file']['name'][0],
            'error' => $_FILES['upload_file']['error'][0],
            'size' => $_FILES['upload_file']['size'][0],
            'type' => $_FILES['upload_file']['type'][0],
        );

        $is_uploaded = $this->makeUpload($file);

        if ($is_uploaded) {
            echo json_encode(array('success' => 'done'));
            exit();
        }

        echo json_encode(array('error' => $file));
        exit();
    }

    public function makeUpload($file)
    {
        if (isset($file['tmp_name'])) {
            $helper = new HelperUploader($file['name']);
            $helper->setPostMaxSize(Tools::getOctets(ini_get('upload_max_filesize')))
                ->setAcceptTypes(self::IMPORT_ALLOWED_FILE_FORMAT)
                ->setSavePath(_AE_IMPORT_PATH_)
                ->upload($file, $file['name']);

            return true;
        }
    }

    public function clearSmartyCache()
    {
        Tools::enableCache();
        Tools::clearCache($this->context->smarty);
        Tools::restoreCacheSettings();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param bool $validateOnly
     * @param int $moreStep
     * @return array
     */
    public function runImport($offset, $limit, $validateOnly, $moreStep, $id)
    {
        $results = array();

        $context = Context::getContext();
        $context->employee = new Employee(1);
        $aeImportController = new AdminAdvancedExportPrestaImportController();

        $aeImportController->importByGroups(
            $offset,
            $limit,
            $results,
            $validateOnly,
            $moreStep
        );

        if (!$validateOnly) {
            $this->correctLog($id);
        }

        // Retrieve errors/warnings if any
        if (count($this->errors) > 0) {
            $results['errors'] = $this->errors;
        }
        if (count($this->warnings) > 0) {
            $results['warnings'] = $this->warnings;
        }
        if (count($this->informations) > 0) {
            $results['informations'] = $this->informations;
        }

        if (!$validateOnly && (bool)$results['isFinished'] && !isset($results['oneMoreStep']) &&
            (bool)Tools::getValue('sendemail')) {
            // Mail::Send() can sometimes throw an error...
            try {
                unset($this->context->cookie->csv_selected); // remove CSV selection file if finished with no error.

                $templateVars = array(
                    '{firstname}' => $this->context->employee->firstname,
                    '{lastname}' => $this->context->employee->lastname,
                    '{filename}' => Tools::getValue('csv'),
                );

                $employeeLanguage = new Language((int)$this->context->employee->id_lang);
                // Mail send in last step because in case of failure, does NOT throw an error.
                $mailSuccess = @Mail::Send(
                    (int)$this->context->employee->id_lang,
                    'import',
                    $this->trans(
                        'Import complete',
                        array(),
                        'Emails.Subject',
                        $employeeLanguage->locale
                    ),
                    $templateVars,
                    $this->context->employee->email,
                    $this->context->employee->firstname . ' ' . $this->context->employee->lastname,
                    null,
                    null,
                    null,
                    null,
                    _PS_MAIL_DIR_,
                    false,
                    // do not die in failed! Warn only, it's not an import error, because import finished in fact.
                    (int)$this->context->shop->id
                );
                if (!$mailSuccess) {
                    $results['warnings'][] = $this->trans(
                        'The confirmation email couldn\'t be sent, but the import is successful. Yay!',
                        array(),
                        'Admin.Advparameters.Notification'
                    );
                }
            } catch (Exception $e) {
                $results['warnings'][] = $this->trans(
                    'The confirmation email couldn\'t be sent, but the import is successful. Yay!',
                    array(),
                    'Admin.Advparameters.Notification'
                );
            }
        }

        die(json_encode($results));
    }

    /**
     * @param null $id
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function addImportGETValues($id = null)
    {
        if (!$id) {
            throw new PrestaShopException('Invalid import id');
        }
        // important to get again so new import_filename exists
        $aeImport = $this->getAdvancedExportImportClass($id);

        $this->addToGet('id', $aeImport->id);
        $this->addToGet('csv', $aeImport->import_filename);
        $this->addToGet('iso_lang', $aeImport->iso_lang);
        $this->addToGet('regenerate', $aeImport->regenerate);
        $this->addToGet('entity', $aeImport->entity);
        $this->addToGet('sendemail', $aeImport->send_email);
        $this->addToGet('separator', $aeImport->separator);
        $this->addToGet('multi_value_separator', $aeImport->multi_value_separator);
        $this->addToGet('skip', $aeImport->skip);
        $this->addToGet('truncate', $aeImport->truncate);

        $this->addMappingToGet($aeImport->mapping);
    }

    /**
     * @param AdvancedExportImportClass $aeImport
     */
    public function addMappingToGet($mapping)
    {
        $mappings = json_decode($mapping, true);

        $query = null;
        foreach ($mappings as $key => $mapping) {
            $query .= 'type_value[' . $key . ']=' . $mapping . '&';
        }

        $query = rtrim($query, "&");
        $type_value = array();
        $type_value[1] = $query;
        $this->addToGETQuery($type_value);
    }

    private function correctLog($id)
    {
        $lastInsertedId = $this->moduleTools->dbGetValue('SELECT MAX(id_log) FROM `' . _DB_PREFIX_ . 'log`');
        $prestaShopLogger = new PrestaShopLogger($lastInsertedId);
        $aeImport = $this->getAdvancedExportImportClass($id);

        if (!$prestaShopLogger->object_type && !$prestaShopLogger->object_id) {
            $prestaShopLogger->object_type = 'AdvancedExportImportClass';
            $prestaShopLogger->object_id = $id;
            $prestaShopLogger->message .= ' file ' .$aeImport->import_filename;
            $prestaShopLogger->save();
        }
    }

        /**
     * @param $id
     * @return AdvancedExportImportClass
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getAdvancedExportImportClass($id)
    {
        $ae = new AdvancedExportImportClass($id);
        if (is_object($ae)) {
            return $ae;
        }

        throw new PrestaShopException('Invalid AdvancedExport object.');
    }

    public function getFiles()
    {
        $files = array_map(
            'basename',
            glob(
                _AE_IMPORT_PATH_.'*.{' . implode(',', self::IMPORT_ALLOWED_FILE_FORMAT). '}',
                GLOB_BRACE
            )
        );

        $files_info = array();
        if ($files) {
            foreach ($files as $value) {
                $files_info[] = array(
                    'name' => $value,
                    'type' => HelperUploader::TYPE_FILE,
                    'size' => 't',
                    'delete_url' => '',
                    'download_url' => '',
                );
            }
        }

        return $files_info;
    }

    public function isPathExists($path)
    {
        if (!$is_exist = file_exists($path)) {
            $this->errors[] = sprintf($this->l('Could not open %s for reading!'), basename($path));
        }

        return $is_exist;
    }
}
