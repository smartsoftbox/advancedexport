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

class NewslettersForm extends MyHelperForm
{
    public function __construct($module)
    {
        parent::__construct($module);
    }

    public function formFields()
    {
        $fields = array(
            array(
                'type' => 'duallist',
                'label' => $this->l('Newsletters fields'),
                'name' => 'fields[]',
                'id' => 'fields',
                'class' => '',
                'multiple' => true,
                'options' => array(
                    'optiongroup' => array(
                        'query' => $this->groupFields(AdvancedExportFieldClass::getAllFields('newsletters')),
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
                'label' => $this->l('Only active'),
                'class' => 't',
                'name' => 'active',
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
        );

        return $fields;
    }
}
