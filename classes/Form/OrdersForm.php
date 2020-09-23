<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once 'MyHelperForm.php';

class OrdersForm extends MyHelperForm
{
    private $selected_cat;

    public function __construct($module)
    {
        parent::__construct($module);
    }

    public function formFields()
    {
        $fields = array(
            array(
                'type' => 'duallist',
                'label' => $this->l('Orders fields'),
                'name' => 'fields[]',
                'id' => 'fields',
                'class' => 'ds-select orders',
                'options' => array(
                    'optiongroup' => array(
                        'query' => $this->groupFields(AdvancedExportFieldClass::getAllFields('orders')),
                        'label' => 'name',
                    ),
                    'options' => array(
                        'query' => 'groups',
                        'id' => 'field',
                        'name' => 'name',
                    ),
                )
            ),
            array(
                'type' => $this->switch,
                'label' => $this->l('Each order in new file'),
                'name' => 'orderPerFile',
                'class' => 't process0',
                'is_bool' => true,
                'desc' => $this->l('Patter will be filename_{order id}'),
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
                'type' => 'select',
                'label' => $this->l('Customers groups'),
                'name' => 'groups[]',
                'id' => 'groups',
                'class' => 'chosen',
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'multiple' => true,
                'options' => array(
                    'query' => Group::getGroups($this->getConfiguration('PS_LANG_DEFAULT')),
                    'id' => 'id_group',
                    'name' => 'name',
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Payments'),
                'name' => 'payments[]',
                'id' => 'payments',
                'class' => 'chosen',
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'multiple' => true,
                'options' => array(
                    'query' => PaymentModule::getInstalledPaymentModules(),
                    'id' => 'name',
                    'name' => 'name',
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Carrier type'),
                'name' => 'carriers[]',
                'id' => 'carriers',
                'class' => 'chosen',
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'multiple' => true,
                'options' => array(
                    'query' => Carrier::getCarriers(
                        $this->getConfiguration('PS_LANG_DEFAULT'),
                        true,
                        false,
                        false,
                        null,
                        5
                    ),
                    'id' => 'id_carrier',
                    'name' => 'name',
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Order state'),
                'name' => 'state[]',
                'id' => 'state',
                'class' => 'chosen',
                'multiple' => true,
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'multiple' => true,
                'options' => array(
                    'query' => OrderState::getOrderStates($this->getConfiguration('PS_LANG_DEFAULT')),
                    'id' => 'id_order_state',
                    'name' => 'name',
                ),
            ),
        );

        return $fields;
    }
}
