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

class CategoriesFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'Id category',
            'field' => 'id_category',
            'database' => 'category',
            'alias' => 'c',
            'import' => 1,
            'import_name' => 'ID',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Id parent',
            'field' => 'id_parent',
            'database' => 'category',
            'alias' => 'c',
            'import' => 4,
            'import_name' => 'Parent category',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Id shop default',
            'field' => 'id_shop_default',
            'database' => 'category',
            'alias' => 'c',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Level depth',
            'field' => 'level_depth',
            'database' => 'category',
            'alias' => 'c',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'nleft',
            'field' => 'nleft',
            'database' => 'category',
            'alias' => 'c',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'nright',
            'field' => 'nright',
            'database' => 'category',
            'alias' => 'c',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'category',
            'alias' => 'c',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Is root category',
            'field' => 'is_root_category',
            'database' => 'category',
            'alias' => 'c',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Id group',
            'field' => 'id_group',
            'database' => 'other',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Id shop',
            'field' => 'id_shop',
            'database' => 'category_lang',
            'alias' => 'cl',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Name',
            'field' => 'name',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 3,
            'import_name' => 'Name',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Description',
            'field' => 'description',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 6,
            'import_name' => 'Description',
            'group15' => CategoryGroup::INFORMATION
        ),
        array(
            'name' => 'Link rewrite',
            'field' => 'link_rewrite',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 10,
            'import_name' => 'URL rewritten',
            'group15' => CategoryGroup::SEO
        ),
        array(
            'name' => 'Meta title',
            'field' => 'meta_title',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 7,
            'import_name' => 'Meta title',
            'group15' => CategoryGroup::SEO
        ),
        array(
            'name' => 'Meta keywords',
            'field' => 'meta_keywords',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 8,
            'import_name' => 'Meta keywords',
            'group15' => CategoryGroup::SEO
        ),
        array(
            'name' => 'Meta description',
            'field' => 'meta_description',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 9,
            'import_name' => 'Meta description',
            'group15' => CategoryGroup::SEO
        ),
        array(
            'name' => 'Position',
            'field' => 'position',
            'database' => 'category_shop',
            'alias' => 'category_shop',
            'group15' => CategoryGroup::SEO
        ),
        array(
            'name' => 'Image URL',
            'field' => 'image',
            'database' => 'other',
            'import' => 11,
            'import_name' => 'Image URL',
            'group15' => CategoryGroup::IMAGE
        )
    );
}
