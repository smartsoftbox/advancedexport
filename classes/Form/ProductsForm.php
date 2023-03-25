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

class ProductsForm extends MyHelperForm
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
                'label' => $this->l('Products fields'),
                'name' => 'fields[]',
                'id' => 'fields',
                'class' => 'ds-select products',
                'multiple' => true,
                'options' => array(
                    'optiongroup' => array(
                        'query' => $this->groupFields(AdvancedExportFieldClass::getAllFields('products')),
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
                'label' => $this->l('Active products only'),
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
                ),
            ),
            array(
                'type' => $this->switch,
                'label' => $this->l('Products out of stock'),
                'name' => 'out_of_stock',
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
                'label' => $this->l('Products with ean13'),
                'name' => 'ean',
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
                'type' => 'select',
                'label' => $this->l('Suppliers'),
                'name' => 'suppliers[]',
                'id' => 'suppliers',
                'class' => 'chosen',
                'multiple' => true,
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'options' => array(
                    'query' => Supplier::getSuppliers(
                        false,
                        $this->getConfiguration('PS_LANG_DEFAULT')
                    ),
                    'id' => 'id_supplier',
                    'name' => 'name',
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Manufacturers'),
                'name' => 'manufacturers[]',
                'id' => 'manufacturers',
                'class' => 'chosen',
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'multiple' => true,
                'options' => array(
                    'query' => Manufacturer::getManufacturers(
                        false,
                        $this->getConfiguration('PS_LANG_DEFAULT')
                    ),
                    'id' => 'id_manufacturer',
                    'name' => 'name',
                ),
            ),
        );
        if (_PS_VERSION_ >= 1.6) {
            $fields[] = array(
                'type' => 'categories',
                'label' => $this->l('Categories'),
                'name' => 'categories',
                'desc' => $this->l('If you want all leave blank. All are exported by default.'),
                'tree' => array(
                    'id' => 'categories-tree',
                    'selected_categories' => $this->getSelectedCat(),
                    'use_search' => true,
                    'use_checkbox' => true,
                ),
            );
        } else {
            $root_category = Category::getRootCategory();
            $root_category = array('id_category' => $root_category->id, 'name' => $root_category->name);

            $fields[] = array(
                'type' => 'categories',
                'label' => $this->l('Parent category:'),
                'name' => 'categories',
                'values' => array(
                    'trads' => array(
                        'Root' => $root_category,
                        'selected' => $this->l('Selected'),
                        'Collapse All' => $this->l('Collapse All'),
                        'Expand All' => $this->l('Expand All'),
                        'Check All' => $this->l('Check All'),
                        'Uncheck All' => $this->l('Uncheck All'),
                    ),
                    'selected_cat' => ($this->selected_cat ? $this->selected_cat : array()),
                    'input_name' => 'categories[]',
                    'disabled_categories' => array(),
                    'use_checkbox' => true,
                    'use_radio' => false,
                    'use_search' => false,
                    'top_category' => Category::getTopCategory(),
                    'use_context' => true,
                ),
            );
        }

        return $fields;
    }

    public function getSelectedCat()
    {
        if(Tools::getValue('id_advancedexport')) {
            $ae = new AdvancedExportClass(Tools::getValue('id_advancedexport'));
            $fields = json_decode($ae->fields, true);
            return (isset($fields['categories']) ? $fields['categories'] : array());
        }
    }
}
