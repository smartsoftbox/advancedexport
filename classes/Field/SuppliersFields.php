<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

include_once 'BaseFields.php';

class SuppliersFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'id supplier',
            'field' => 'id_supplier',
            'database' => 'supplier',
            'alias' => 's',
            'import' => 1,
            'import_name' => 'ID',
            'group15' => SupplierGroup::INFORMATION
        ),
        array(
            'name' => 'name',
            'field' => 'name',
            'database' => 'supplier',
            'alias' => 's',
            'import' => 3,
            'import_name' => 'Name *',
            'group15' => SupplierGroup::INFORMATION
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'supplier',
            'alias' => 's',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'group15' => SupplierGroup::INFORMATION
        ),
        array(
            'name' => 'description',
            'field' => 'description',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 4,
            'import_name' => 'Description',
            'group15' => SupplierGroup::INFORMATION
        ),
        array(
            'name' => 'meta title',
            'field' => 'meta_title',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 6,
            'import_name' => 'Meta title',
            'group15' => SupplierGroup::SEO
        ),
        array(
            'name' => 'meta keywords',
            'field' => 'meta_keywords',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 7,
            'import_name' => 'Meta keywords',
            'group15' => SupplierGroup::SEO
        ),
        array(
            'name' => 'meta description',
            'field' => 'meta_description',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 8,
            'import_name' => 'Meta description',
            'group15' => SupplierGroup::SEO
        ),
        array(
            'name' => 'id shop',
            'field' => 'id_shop',
            'database' => 'supplier',
            'alias' => 'supplier_shop',
            'group15' => SupplierGroup::INFORMATION
        ),
        array(
            'name' => 'Image URL',
            'field' => 'image',
            'database' => 'other',
            'import' => 9,
            'import_name' => 'Image URL',
            'group15' => SupplierGroup::IMAGE
        )
    );
}
