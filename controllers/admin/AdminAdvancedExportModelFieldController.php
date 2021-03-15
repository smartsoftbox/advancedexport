<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/AdminAdvancedExportBaseController.php';

class AdminAdvancedExportModelFieldController extends AdminAdvancedExportBaseController
{
    public $type;

    public function __construct()
    {
        $this->table = 'advancedexportfield';
        $this->className = 'AdvancedExportFieldClass';
        $this->fields_list = array(
            'id_advancedexportfield' => array(
                'title' => 'ID',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'type' => 'editable',
                'class' => 'another-custom_class',
            ),
            'field' => array(
                'title' => $this->l('Field'),
            ),
            'table' => array(
                'title' => $this->l('Table'),
            ),
            'return' => array(
                'title' => $this->l('Return value'),
                'type' => 'editable',
                'class' => 'ds-return',
            ),
        );

        $this->context = Context::getContext();
        $this->context->controller = $this;

        parent::__construct();

        $this->type = $this->getEntity();

        $this->_where = 'AND a.`tab` = "' . $this->type . '"';

        $this->bootstrap = true;

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Would you like to delete the selected items?'),
            )
        );
    }

    public function processSave()
    {
        $id = Tools::getValue('id_advancedexportfield');
        $field = new AdvancedExportFieldClass($id);
        $field->name = Tools::getValue('name');

        if ($field->isCustom !== '0' && $field->table !== 'other') {
            $field->tab = Tools::getValue('type');
            $field->name = Tools::getValue('name');
            $field->return = Tools::getValue('return');
            $field->table = 'static';
            $field->isCustom = 1;
            if ($id) {
                $field->field = 'field_' . $field->id;
            } else {
                $field->save();
                $field->field = 'field_' . $field->id;
            }
        }

        $field->save();

        Tools::redirectAdmin(
            Context::getContext()->link->getAdminLink(_ADMIN_AE_MODEL_FIELD_, true) . '&conf=3'
        );
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (isset($this->toolbar_btn['new'])) {
            $this->toolbar_btn['new']['href'] = $this->context->link->getAdminLink(_ADMIN_AE_MODEL_FIELD_) .
                '&addadvancedexportfield&type=' . $this->type;
        }

        if (isset($this->toolbar_btn['back'])) {
            $this->toolbar_btn['back'] = array(
                'href' => $this->context->link->getAdminLink(_ADMIN_AE_),
                'desc' => $this->l('Back to list.'),
            );
        }
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Settings Form'),
                'icon' => 'icon-envelope',
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_advancedexportfield',
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
                    'desc' => $this->l('Field name'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $id = Tools::getValue('id_advancedexportfield');
        $field = new AdvancedExportFieldClass($id);

        if ($field->isCustom && $field->table !== "other" or !$id && $field->table !== "other") {
            $this->fields_form['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Return value'),
                'name' => 'return',
                'desc' => $this->l('Return value'),
            );
        }

        $this->fields_value['type'] = $field->tab;

        return parent::renderForm();
    }
}
