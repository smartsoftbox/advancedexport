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

class ManufacturersFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'id manufacturer',
            'field' => 'id_manufacturer',
            'database' => 'manufacturer',
            'alias' => 'm',
            'import' => 1,
            'import_name' => 'ID',
            'group15' => ManufacturerGroup::INFORMATION
        ),
        array(
            'name' => 'name',
            'field' => 'name',
            'database' => 'manufacturer',
            'alias' => 'm',
            'import' => 3,
            'import_name' => 'Name *',
            'group15' => ManufacturerGroup::INFORMATION
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'manufacturer',
            'alias' => 'm',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'group15' => ManufacturerGroup::INFORMATION
        ),
        array(
            'name' => 'description',
            'field' => 'description',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 4,
            'import_name' => 'Description',
            'group15' => ManufacturerGroup::INFORMATION
        ),
        array(
            'name' => 'short description',
            'field' => 'short_description',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 5,
            'import_name' => 'Short description',
            'group15' => ManufacturerGroup::INFORMATION
        ),
        array(
            'name' => 'meta title',
            'field' => 'meta_title',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 6,
            'import_name' => 'Meta title',
            'group15' => ManufacturerGroup::SEO
        ),
        array(
            'name' => 'meta keywords',
            'field' => 'meta_keywords',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 7,
            'import_name' => 'Meta keywords',
            'group15' => ManufacturerGroup::SEO
        ),
        array(
            'name' => 'meta description',
            'field' => 'meta_description',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 8,
            'import_name' => 'Meta description',
            'group15' => ManufacturerGroup::SEO
        ),
        array(
            'name' => 'id shop',
            'field' => 'id_shop',
            'database' => 'manufacturer',
            'alias' => 'manufacturer_shop',
            'group15' => ManufacturerGroup::INFORMATION
        ),
        array(
            'name' => 'Image URL',
            'field' => 'image',
            'database' => 'other',
            'import' => 9,
            'import_name' => 'Image URL',
            'group15' => ManufacturerGroup::IMAGE
        )
    );
}
