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
        $this->type = $this->getEntity();

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
        $this->fields_form = $this->getFormFields();

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

        parent::__construct();
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

    public function getFormFields()
    {
        $result = array(
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
                array(
                    'type' => 'text',
                    'label' => $this->l('Return value'),
                    'name' => 'return',
                    'desc' => $this->l('Return value'),
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return $result;
    }

    public function displayAjaxGetExportForm()
    {
        echo $this->renderList();
    }
}
