<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../classes/Form/MyHelperForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/ProductsForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/AddressesForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/CategoriesForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/CustomersForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/ManufacturersForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/NewslettersForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/NewslettersForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/OrdersForm.php';
require_once dirname(__FILE__) . '/../../classes/Form/SuppliersForm.php';

require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';

require_once dirname(__FILE__) . '/../../classes/Data/Charset.php';
require_once dirname(__FILE__) . '/../../classes/Data/SaveType.php';
require_once dirname(__FILE__) . '/../../classes/Export/Export.php';

require_once dirname(__FILE__) . '/../../classes/ModuleTools.php';
require_once dirname(__FILE__) . '/AdminAdvancedExportBaseController.php';
require_once dirname(__FILE__) . '/AdminAdvancedExportModelFileController.php';

class AdminAdvancedExportModelController extends AdminAdvancedExportBaseController
{
    public $showTimeAndMemory;
    public $type;

    public function __construct()
    {
        $this->table = 'advancedexport';
        $this->className = 'AdvancedExportClass';
        $this->showTimeAndMemory = false;
        $this->fields_list = array(
            'id_advancedexport' => array(
                'title' => $this->l('ID'),
                'width' => 40,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 100,
            ),
            'cron_url' => array(
                'title' => $this->l('Cron url'),
                'type' => 'html',
                'orderby' => false,
                'search' => false,
            ),
            'save_type' => array(
                'title' => $this->l('Save Type'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
//            'only_new' => array(
//                'title' => $this->l('New entries'),
//                'active' => 'only_new',
//                'type' => 'bool',
//                'align' => 'center',
//                'width' => 30,
//                'orderby' => false,
//                'search' => false,
//            ),
        );

        $this->context = Context::getContext();
        $this->context->controller = $this;

        parent::__construct();

        $this->type = $this->getEntity();
        $this->list_id = $this->type . 'export';

        //have to be after list_id is set
        parent::processFilter();

        $this->_where = 'AND a.`type` = "' . $this->type . '"';

        $this->bootstrap = true;

        $this->addRowAction('export');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Would you like to delete the selected items?'),
            )
        );

        $this->tpl_form_vars = array(
            'link' => Context::getContext()->link->getAdminLink(
                _ADMIN_AE_MODEL_FIELD_,
                true
            ) . '&type=' . $this->type
        );
    }

    public function displayExportLink($token, $id)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_export.tpl');

        $tpl->assign(array(
            'href' => self::$currentIndex . '&token=' . $token . '&' . $this->identifier .
                '=' . $id . '&export' . $this->table . '=1',
            'action' => $this->l('Export'),
            'is_presta_16' => (_PS_VERSION_ >= 1.6 ? true : false)
        ));

        return $tpl->fetch();
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (isset($this->toolbar_btn['new'])) {
            $this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink(_ADMIN_AE_MODEL_) .
                '&addadvancedexport&type=' . $this->type;
        }

        if ($this->type != 'orders' && $this->type != 'newsletters') {
            $this->toolbar_btn['import'] = array(
                'href' => $this->context->link->getAdminLink(_ADMIN_AE_MODEL_) .
                    '&generate=1&type=' . $this->type,
                'desc' => $this->l('Generate import models'),
            );
        }

        $this->toolbar_btn['edit'] = array(
            'href' => $this->context->link->getAdminLink(_ADMIN_AE_MODEL_FIELD_) . '&type=' . $this->type,
            'desc' => $this->l('Edit export fields.'),
        );

        if (isset($this->toolbar_btn['back'])) {
            $this->toolbar_btn['back'] = array(
                'href' => $this->context->link->getAdminLink(_ADMIN_AE_),
                'desc' => $this->l('Back to list.'),
            );
        }
    }

    public function renderForm()
    {
        $specific = $this->getExportModelFormSpecificFields($this->type);

        $this->switch = (_PS_VERSION_ >= 1.6 ? 'switch' : 'radio');

        $result = array(
            'legend' => array(
                'title' => $this->l('Settings Form'),
                'icon' => 'icon-envelope',
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_advancedexport',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'type',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'validation' => 'isName',
                    'name' => 'name',
                    'required' => true,
                    'desc' => $this->l('Settings insternal name'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('File name'),
                    'name' => 'filename',
                    'desc' => $this->l('You can set name for file or leave blank name will be given by system.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('File format'),
                    'name' => 'file_format',
                    'options' => array(
                        'query' => array(
                            array(
                                'value' => 'csv',
                                'name' => $this->l('CSV'),
                            ),
                            array(
                                'value' => 'xlsx',
                                'name' => $this->l('Excel 97 and above (.xlsx)'),
                            ),
                            array(
                                'value' => 'ods',
                                'name' => $this->l('Open Document Format/OASIS (.ods)'),
                            ),
                        ),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('This is available for PrestaShop 1.7 and above.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Delimiter'),
                    'name' => 'delimiter',
                    'options' => array(
                        'query' => array(
                            array(
                                'value' => ',',
                                'name' => '[,] ' . $this->l('comma'),
                            ),
                            array(
                                'value' => ';',
                                'name' => '[;] ' . $this->l('semi-colons'),
                            ),
                            array(
                                'value' => ':',
                                'name' => '[:] ' . $this->l('colons'),
                            ),
                            array(
                                'value' => '|',
                                'name' => '[|] ' . $this->l('pipes'),
                            ),
                            array(
                                'value' => '~',
                                'name' => '[~] ' . $this->l('tilde'),
                            ),
                        ),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Separrator for each line in csv file'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Separator'),
                    'name' => 'separator',
                    'options' => array(
                        'query' => array(
                            array(
                                'value' => '&#34;',
                                'name' => '["] ' . $this->l('quotation marks'),
                            ),
                            array(
                                'value' => "'",
                                'name' => "['] " . $this->l('single quotation marks'),
                            ),
                            // array(
                            //  'value' => '',
                            //     'name' => $this->l('no separator'),
                            // )
                        ),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Separrator for each value in csv file'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Language'),
                    'name' => 'id_lang',
                    'col' => '4',
                    'options' => array(
                        'query' => Language::getLanguages(),
                        'id' => 'id_lang',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Default utf-8.'),

                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Encoding type'),
                    'name' => 'charset',
                    'col' => '4',
                    'options' => array(
                        'query' => Charset::getCharsets(),
                        'id' => 'name',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Default utf-8.'),

                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Decimal separator'),
                    'name' => 'decimal_separator',
                    'options' => array(
                        'query' => array(
                            array(
                                'value' => ',',
                                'name' => '[,] ' . $this->l('comma'),
                            ),
                            array(
                                'value' => '.',
                                'name' => '[.] ' . $this->l('dot'),
                            ),
                        ),
                        'default' => array(
                            'label' => $this->l('default'),
                            'value' => -1,
                        ),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Round values'),
                    'name' => 'decimal_round',
                    'options' => array(
                        'query' => array(
                            array(
                                'value' => '0',
                                'label' => '0',
                            ),
                            array(
                                'value' => '1',
                                'label' => '1',
                            ),
                            array(
                                'value' => '2',
                                'label' => '2',
                            ),
                            array(
                                'value' => '3',
                                'label' => '3',
                            ),
                            array(
                                'value' => '4',
                                'label' => '4',
                            ),
                            array(
                                'value' => '5',
                                'label' => '5',
                            ),
                            array(
                                'value' => '6',
                                'label' => '6',
                            ),
                        ),
                        'default' => array(
                            'label' => $this->l('default'),
                            'value' => -1,
                        ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Save type'),
                    'name' => 'save_type',
                    'default' => '0',
                    'options' => array(
                        'query' => SaveType::getSaveTypes(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Save your file, sent to server or email.'),

                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Email'),
                    'name' => 'email',
                    'class' => 'process2 input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Hostname'),
                    'name' => 'ftp_hostname',
                    'class' => 'process1 input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Port'),
                    'placeholder' => 21,
                    'name' => 'ftp_port',
                    'class' => 'process1 input fixed-width-xs other-border',
                    'desc' => $this->l('Leave blank then default will be used (ftp:21 / sftp:22).'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Username'),
                    'name' => 'ftp_user_name',
                    'class' => 'process1 input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Password'),
                    'name' => 'ftp_user_pass',
                    'class' => 'process1 input fixed-width-xxl other-border',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Path'),
                    'name' => 'ftp_directory',
                    'class' => 'process1 other-border',
                ),
                array(
                    'type' => 'free',
                    'name' => 'test_connection',
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Display labels'),
                    'name' => 'add_header',
                    'class' => 't',
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
                    'label' => $this->l('Strip tags'),
                    'name' => 'strip_tags',
                    'class' => 't',
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
                    'label' => $this->l('Export not exported'),
                    'name' => 'only_new',
                    'class' => 't',
                    'is_bool' => true,
                    'desc' => $this->l('Export not exported yet.'),
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
                    'type' => 'free',
                    'name' => 'id',
                    'label' => $this->l('Id'),
                    'desc' => $this->l('You can specify start id number.')
                ),
                array(
                    'type' => 'free',
                    'name' => 'date',
                    'label' => $this->l('Date add'),
                    'desc' => $this->l('Format: 2012-12-31 HH-MM-SS(inclusive).')
                )
            ),
            'buttons' => array(
                'cancelBlock' => array(
                    'title' => $this->l('Cancel'),
                    'href' => $this->context->link->getAdminLink('AdminAdvancedExport'),
                    'icon' => 'process-icon-cancel'
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        if ($this->type != 'orders' && $this->type != 'newsletters' &&
            $this->type != 'customers' && $this->type != 'addresses') {
            $result['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Image type'),
                'name' => 'image_type',
                'col' => '4',
                'options' => array(
                    'query' => ImageType::getImagesTypes($this->type),
                    'id' => 'name',
                    'name' => 'name',
                ),
            );
        }

        $result['input'] = array_merge($result['input'], $specific);

        $this->fields_form = $result;
        $this->show_form_cancel_button = false;
        return parent::renderForm();
    }

    public function displayAjaxGetExportForm()
    {
        $models = $this->renderList();
        $aeFile = new AdminAdvancedExportModelFileController();
        $files = $aeFile->renderList();
        echo $models . $files;
    }

    public function displayAjaxCheckConnection()
    {
        echo $this->checkConnection();
    }

    public function getFieldsValue($ae)
    {
        parent::getFieldsValue($ae);

        $fields_specific = Tools::jsonDecode($ae->fields, true);
        $specific = $this->getExportModelFormSpecificFields($this->type);

        foreach ($specific as $value) {
            if (isset($fields_specific[$value['name']])) {
                $this->fields_value[$value['name']] = $fields_specific[$value['name']];
            } else {
                // fields needs to be array because in smarty we check if it is
                // in array
                if ($value['name'] === 'fields[]') {
                    $this->fields_value[$value['name']] = array();
                } else {
                    $this->fields_value[$value['name']] = null;
                }
            }
        }

        $this->fields_value['test_connection'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ .
            'advancedexport/views/templates/admin/test_connection_button.tpl');

        $this->fields_value['id'] = $this->createFromToField(
            'start_id',
            $ae->start_id,
            'end_id',
            $ae->end_id
        );
        $this->fields_value['date'] = $this->createFromToField(
            'date_from',
            $ae->date_from,
            'date_to',
            $ae->date_to,
            (_PS_VERSION_ >= 1.6 ? 'datetimepicker' : 'datepicker')
        );
        if (isset($fields_specific['categories'])) {
            $this->selected_cat = $fields_specific['categories'];
        }

        //fix for select default value
        if ($this->fields_value['decimal_round'] === false) {
            $this->fields_value['decimal_round'] = -1;
        }

        return $this->fields_value;
    }

    protected function createFromToField($from_name, $from_value, $to_name, $to_value, $class = '')
    {
        $this->context->smarty->assign(array(
            'from_name' => $from_name,
            'from_value' => $from_value,
            'to_name' => $to_name,
            'to_value' => $to_value,
            'class' => $class
        ));

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'advancedexport/views/templates/admin/fromto.tpl'
        );
    }

    public function processDelete()
    {
        parent::processDelete();

        $this->redirect_after = Context::getContext()->link->getAdminLink(_ADMIN_AE_, true)
            . '&conf=1';
    }

    public function processBulkDelete()
    {
        if (_PS_VERSION_ >= 1.6) {
            $res = false;
            $export_models = Tools::getValue($this->type . 'exportBox');
            foreach ($export_models as $export_model) {
                $ae = new AdvancedExportClass($export_model);
                $res = $ae->delete();
            }
        } else {
            parent::processBulkDelete();
        }

        $this->redirect_after = Context::getContext()->link->getAdminLink(_ADMIN_AE_, true)
            . '&conf=1';

        return $res;
    }

    public function processSave()
    {
        $fields = Tools::getValue('fields');
        if (empty($fields) or $fields[0] === '{}') {
            $this->errors[] = $this->l('You must choose at least one field.');
        }
        if (!Validate::isGenericName(Tools::getValue('name')) || Tools::getValue('name') == '') {
            $this->errors[] = $this->l('Invalid or empty name.');
        }
        if (Tools::getValue('filename') != '' && !Validate::isGenericName(Tools::getValue('filename'))) {
            $this->errors[] = $this->l('Invalid file name.');
        }
        if (Tools::getValue('date_from') != '' && !Validate::isDate(Tools::getValue('date_from'))) {
            $this->errors[] = $this->l('Invalid date from.');
        }
        if (Tools::getValue('date_to') != '' && !Validate::isDate(Tools::getValue('date_to'))) {
            $this->errors[] = $this->l('Invalid date to.');
        }
        if (Tools::getValue('start_id') != '' && !Validate::isInt(Tools::getValue('start_id'))) {
            $this->errors[] = $this->l('Invalid begin id field.');
        }
        if (Tools::getValue('end_id') != '' && !Validate::isInt(Tools::getValue('end_id'))) {
            $this->errors[] = $this->l('Invalid finish id field.');
        }

        if (empty($this->errors)) {
            if ($this->saveModel()) {
                Tools::redirectAdmin(
                    Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=3'
                );
            }
        } else {
            // if we have errors, we stay on the form instead of going back to the list
            $this->display = 'edit';
        }
    }

    public function postProcess()
    {
        parent::postProcess();

        if (Tools::isSubmit('generate')) {
            $this->generateDefaultCsvForImport($this->type);

            $this->redirect_after = Context::getContext()->link->getAdminLink(
                _ADMIN_AE_,
                true
            ) . $this->getFilters();
        }
    }

    public function processFilter()
    {
        // Dont' put here processFilter
        // it is in construct
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

    public function generateDefaultCsvForImport($type)
    {
        if ($type == 'products') {
            $this->generateCombination($type);
        }

        return $this->generateDefaultCsvByType($type);
    }

    /**
     * @param $type
     *
     * @return AdvancedExportClass
     * @throws PrestaShopException
     * @internal param $combination_fields
     */
    public function generateCombination($type)
    {
        $combination_fields = AdvancedExportFieldClass::getDefaultCombinationImportFields($type);

        $ae = new AdvancedExportClass();
        $ae->delimiter = ',';
        $ae->separator = '"';
        $ae->add_header = true;
        $ae->id_lang = Configuration::get('PS_LANG_DEFAULT');
        $ae->charset = 'UTF-8';
        $ae->decimal_round = -1;
        $ae->decimal_separator = -1;
        $ae->strip_tags = 0;
        $ae->only_new = 0;
        $ae->last_exported_id = 0;
        $ae->start_id = 0;
        $ae->end_id = 0;
        $ae->type = 'products';
        $ae->name = 'combination_import';
        $ae->filename = 'combination_import';
        $ae->file_format = 'csv';
        $ae->fields = Tools::jsonEncode(
            array(
                'fields[]' => $combination_fields,
                'attributes' => 1,
            )
        );
        $ae->add();

        return $ae;
    }

    /**
     * @param $type
     *
     * @return AdvancedExportClass
     * @throws PrestaShopException
     */
    public function generateDefaultCsvByType($type)
    {
        $fields = AdvancedExportFieldClass::getDefaultImportFields($type);

        $ae = new AdvancedExportClass();
        $ae->delimiter = ',';
        $ae->separator = '"';
        $ae->add_header = true;
        $ae->id_lang = Configuration::get('PS_LANG_DEFAULT');
        $ae->charset = 'UTF-8';
        $ae->decimal_round = -1;
        $ae->decimal_separator = -1;
        $ae->strip_tags = 0;
        $ae->only_new = 0;
        $ae->last_exported_id = 0;
        $ae->start_id = 0;
        $ae->end_id = 0;
        $ae->type = $type;
        $ae->name = $type . '_import';
        $ae->filename = $type . '_import';
        $ae->file_format = 'csv';
        $ae->fields = Tools::jsonEncode(array('fields[]' => $fields));
        $ae->add();

        return $ae;
    }

    public function processExport($text_delimiter = '"')
    {
        $time_start = microtime(true);  //debug

        $this->getExportType(Tools::getValue('id_advancedexport'));

        if ($this->showTimeAndMemory) {
            $this->showTimeAndMemoryUsage($time_start);
        } else {
            $this->moduleTools->redirectAdmin(
                Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=3'
            );
        }
    }

    public function showTimeAndMemoryUsage($time_start)
    {
        $time_end = microtime(true);
        //dividing with 60 will give the execution time in minutes other wise seconds
        $execution_time = ($time_end - $time_start) / 60;

        $this->smarty->assign(array(
            'memory_get_peak_usage' => _MODULE_DIR_,
            'time_end' => $time_end,
            'execution_time' => $execution_time
        ));

        echo $this->display(__FILE__, 'views/templates/admin/memory.tpl');
    }

    public function getExportType($id_advancedexport)
    {
        $export = new Export();
        $export->createExportFile(new AdvancedExportClass($id_advancedexport));
    }

    public function saveModel()
    {
        $form_name = Tools::ucfirst(Tools::getValue('type')) . 'Form';
        $form = new $form_name($this);
        $specific = $form->formFields();

        $to_serialize = null;
        foreach ($specific as $value) {
            $trimmed = str_replace('[]', '', $value['name']);
            if (Tools::getValue($trimmed) != '') {
                if ((string)$trimmed === 'fields') {
                    $fields = Tools::getValue($trimmed);
                    // for backwards compatibility we have to leave field name to fields[]
                    $to_serialize[$value['name']] = json_decode($fields[0]);
                } else {
                    $to_serialize[$value['name']] = Tools::getValue($trimmed);
                }
            }
        }

        $ae = new AdvancedExportClass(Tools::getValue('id_advancedexport'));
        $ae->copyFromPost();
        $ae->fields = Tools::jsonEncode($to_serialize);
        $ae->save();

        return true;
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();

        $this->content .= '<script type="text/javascript">
            var ae_controller_model_url = "' . $this->context->link->getAdminLink(_ADMIN_AE_MODEL_) . '";
            var ae_controller_model = "' . _ADMIN_AE_MODEL_ . '";
        </script>';

        $this->path = __PS_BASE_URI__ . 'modules/advancedexport/';

        $this->content .= $this->addJavaScriptVariable();
        $this->context->controller->addCSS($this->path . 'views/css/admin.css');
        $this->context->controller->addCSS($this->path . 'views/css/duallist.css');
        $this->context->controller->addCSS($this->path . 'views/css/bootstrap-editable.css');
        $this->context->controller->addCSS($this->path . 'views/css/jquery.percentageloader-0.1.css');

        if (_PS_VERSION_ >= 1.6) {
            $this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/ui/jquery.ui.sortable.min.js');
            $this->context->controller->addJS($this->path . 'views/js/admin.js');
            $this->context->controller->addJS($this->path . 'views/js/test_connection.js');
        } else {
            $this->context->controller->addJS($this->path . 'views/js/jquery-ui-1.10.4.custom.min.js');
            $this->context->controller->addJS($this->path . 'views/js/fixadmin.js');
            $this->context->controller->addCSS($this->path . 'views/css/fixadmin.css');
            $this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/ui/jquery.ui.datepicker.min.js');
            $this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/ui/themes/base/jquery.ui.datepicker.css');
            $this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/ui/themes/base/jquery.ui.theme.css');
        }

        $this->context->controller->addJS($this->path . 'views/js/duallist.js');
        $this->context->controller->addJS($this->path . 'views/js/selectall.chosen.js');
        $this->context->controller->addJS($this->path . 'views/js/jquery.percentageloader-0.1.min.js');

        $this->context->controller->addJS($this->path . 'views/js/jquery.cooki-plugin.js');
        $this->context->controller->addJS($this->path . 'views/js/clipboard.min.js');
    }

//    public function processFilter()
//    {
//        parent::processFilter();
//
//        if (Tools::getValue('submitFilter' . $this->list_id) || Tools::getValue($this->list_id . 'Orderby') ||
//            Tools::getValue($this->list_id . 'Orderway')) {
//            $this->redirect_after = Context::getContext()->link->getAdminLink(_ADMIN_AE_, true);
//        }
//    }

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
            $this->_list = array_map(function ($export_model) {
                $type = SaveType::getSaveTypes();
                $export_model['cron_url'] = self::getCronLink($export_model);
                $export_model['save_type'] = $type[$export_model['save_type']]['short_name'];

                return $export_model;
            }, $this->_list);
        }
    }

    /**
     * @param $export_model
     * @return string
     */
    public static function getCronLink($export_model)
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/advancedexport/cron.php?secure_key=' .
            Configuration::get('ADVANCEDEXPORT_SECURE_KEY') . '&id=' . $export_model['id_advancedexport'];
    }

    /**
     * @return mixed
     */
    public function getExportModelFormSpecificFields($type)
    {
        $className = Tools::ucfirst($type) . "Form";
        $specific_export_fields = $this->getExportModelFormObject($className);

        return $specific_export_fields->formFields();
    }

    public function getExportModelFormObject($className)
    {
        return new $className($this->module);
    }
}
