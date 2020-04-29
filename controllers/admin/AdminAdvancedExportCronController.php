<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportCronClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportImportClass.php';
require_once dirname(__FILE__) . '/../../classes/Data/Cron.php';
require_once dirname(__FILE__) . '/AdminAdvancedExportBaseController.php';

class AdminAdvancedExportCronController extends AdminAdvancedExportBaseController
{
    public function __construct()
    {
        $this->table = 'advancedexportcron';
        $this->className = 'AdvancedExportCronClass';
        $this->fields_list = array(
            'id_advancedexportcron' => array(
                'title' => $this->l('ID'),
                'width' => 20,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 100,
            ),
            'model_name' => array(
                'title' => $this->l('Model name'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
            'cron_hour' => array(
                'title' => $this->l('Hour'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
            'cron_day' => array(
                'title' => $this->l('Day'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
            'cron_week' => array(
                'title' => $this->l('Week'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
            'cron_month' => array(
                'title' => $this->l('Mouth'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
            'last_export' => array(
                'title' => $this->l('Last Export'),
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'active' => 'active',
                'type' => 'bool',
                'width' => 30,
                'orderby' => false,
                'search' => false,
            ),
        );

        $this->context = Context::getContext();
        $this->context->controller = $this;

        $this->fields_form = $this->getFormFields();

        $this->bootstrap = true;
        $this->addRowAction('edit');
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

    public function processSave()
    {
        if ($this->saveCron()) {
            Tools::redirectAdmin(
                Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=3'
            );
        }
    }

    public function processBulkEnableSelection()
    {
        if (parent::processBulkEnableSelection()) {
            Tools::redirectAdmin(
                Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=4'
            );
        }
    }

    public function processBulkDisableSelection()
    {
        if (parent::processBulkDisableSelection()) {
            Tools::redirectAdmin(
                Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=4'
            );
        }
    }

    public function processDelete()
    {
        if (parent::processDelete()) {
            Tools::redirectAdmin(
                Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=1'
            );
        }
    }

    public function processBulkDelete()
    {
        if (parent::processBulkDelete()) {
            Tools::redirectAdmin(
                Context::getContext()->link->getAdminLink(_ADMIN_AE_, true) . '&conf=1'
            );
        }
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
            $this->_list = array_map(function ($model) {
                if ($model['is_import']) {
                    $ae = new AdvancedExportImportClass($model['id_model']);
                } else {
                    $ae = new AdvancedExportClass($model['id_model']);
                }
                $model['model_name'] = ($model['is_import'] ? 'import ' : 'export ') . $ae->name;

                return $model;
            }, $this->_list);
        }
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (isset($this->toolbar_btn['back'])) {
            $this->toolbar_btn['back'] = array(
                'href' => $this->context->link->getAdminLink(_ADMIN_AE_),
                'desc' => $this->l('Back to list.'),
            );
        }
    }

    public function displayAjaxGetExportForm()
    {
        echo $this->renderList();
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

    public function getFormFields()
    {
        $cron = new Cron();
        $this->switch = (_PS_VERSION_ >= 1.6 ? 'switch' : 'radio');

        $result = array(
            'legend' => array(
                'title' => $this->l('Cron task'),
                'icon' => 'icon-envelope',
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'type',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                    'desc' => $this->l('Cron task name'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Model'),
                    'name' => 'id_model',
                    'class' => 'chosen',
                    'options' => array(
                        'optiongroup' => array(
                            'query' => $this->getAll(),
                            'label' => 'name',
                        ),
                        'options' => array(
                            'query' => 'groups',
                            'id' => 'id_model',
                            'name' => 'name',
                        ),
                    ),
                    'desc' => $this->l('You can set name for file or leave blank name will be given by system.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hour'),
                    'name' => 'cron_hour',
                    'options' => array(
                        'query' => $cron->getCronHour(),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('You can set name for file or leave blank name will be given by system.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Day'),
                    'name' => 'cron_day',
                    'options' => array(
                        'query' => $cron->getCronDay(),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('You can set name for file or leave blank name will be given by system.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Week'),
                    'name' => 'cron_week',
                    'options' => array(
                        'query' => $cron->getCronWeek(),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('You can set name for file or leave blank name will be given by system.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Month'),
                    'name' => 'cron_month',
                    'options' => array(
                        'query' => $cron->getCronMonth(),
                        'id' => 'value',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('You can set name for file or leave blank name will be given by system.'),
                ),
                array(
                    'type' => $this->switch,
                    'label' => $this->l('Active'),
                    'name' => 'active',
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
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return $result;
    }

    /**
     * @return array|false|mysqli_result|PDOStatement|resource|null
     */
    public function getAll()
    {
        $grouped = array(
            'export' => array(
                'name' => 'export',
                'groups' => $this->getExportModels(),
            )
        );

        if (_PS_VERSION_ > 1.6) {
            $grouped = array_merge($grouped, array(
                'import' => array(
                    'name' => 'import',
                    'groups' => $this->getImportModels(),
                ),
            ));
        }

        return $grouped;
    }

    private function saveCron()
    {
        $model = Tools::getValue('id_model');
        list($type, $id) = explode('-', $model);

        $ae_cron = new AdvancedExportCronClass(Tools::getValue('id_advancedexportcron'));
        $ae_cron->copyFromPost();
        $ae_cron->is_import = ($type === 'import');
        $ae_cron->id_model = $id;
        $ae_cron->save();

        return true;
    }

    public function getFieldsValue($obj)
    {
        $fields_values = parent::getFieldsValue($obj); // TODO: Change the autogenerated stub
        $fields_values['id_model'] = ($obj->is_import ? 'import-' : 'export-') . $obj->id_model;

        return $fields_values;
    }

    /**
     * @return array|false|mysqli_result|PDOStatement|resource|null
     */
    public function getExportModels()
    {
        $models_export = AdvancedExportClass::getAll();
        $models_export = array_map(function ($model_export) {
            $model_export['id_model'] = 'export-' . $model_export['id_advancedexport'];
            return $model_export;
        }, $models_export);

        return $models_export;
    }

    /**
     * @return array|false|mysqli_result|PDOStatement|resource|null
     */
    public function getImportModels()
    {
        $models_import = AdvancedExportImportClass::getAll();
        $models_import = array_map(function ($model_import) {
            $model_import['id_model'] = 'import-' . $model_import['id_advancedexportimport'];
            return $model_import;
        }, $models_import);

        return $models_import;
    }
}
