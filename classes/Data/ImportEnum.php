<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class ImportEnum
{
    private static $entities = array(
        array(
            'entity' => 'categories',
            'delete' => true,
            'skip' => true,
            'force' => true,
            'isMultiLang' => true,
            'id' => 0
        ),
        array(
            'entity' => 'products',
            'delete' => true,
            'skip' => true,
            'force' => true,
            'isMultiLang' => true,
            'match_ref' => true,
            'id' => 1
        ),
        array(
            'entity' => 'combinations',
            'delete' => true,
            'isMultiLang' => false,
            'match_ref' => true,
            'id' => 2
        ),
        array(
            'entity' => 'customers',
            'delete' => true,
            'force' => true,
            'isMultiLang' => false,
            'id' => 3
        ),
        array(
            'entity' => 'addresses',
            'delete' => true,
            'force' => true,
            'isMultiLang' => false,
            'id' => 4
        ),
        array(
            'entity' => 'brands',
            'delete' => true,
            'skip' => true,
            'force' => true,
            'isMultiLang' => false,
            'id' => 5
        ),
        array(
            'entity' => 'suppliers',
            'delete' => true,
            'skip' => true,
            'force' => true,
            'isMultiLang' => false,
            'id' => 6
        ),
//        array(
//            'entity' => 'alias',
//            'delete' => true,
//            'force' => true,
//            'isMultiLang' => false,
//            'id' => 7
//        ),
//        array(
//            'entity' => 'store contacts',
//            'skip' => true,
//            'force' => true,
//            'isMultiLang' => false,
//            'id' => 8
//        )
    );

    public function getFields()
    {
        return self::$entities;
    }

    public static function getEntityById($id)
    {
        if (is_null($id) or !isset(self::$entities[$id])) {
            throw new PrestaShopException('Invalid import enum id');
        }

        return self::$entities[$id]['entity'];
    }
}
