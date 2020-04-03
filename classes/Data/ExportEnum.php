<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

include_once 'BasicEnum.php';

abstract class ExportEnum
{
    public static $export_entities = array(
        'products' => 'product',
        'orders' => 'order',
        'categories' => 'category',
        'manufacturers' => 'manufacturer',
        'newsletters' => '',
        'suppliers' => 'supplier',
        'customers' => 'customer',
        'addresses' => 'address'
    );

    /**
     * @return array
     */
    public static function getExportEntities()
    {
        return array_keys(self::$export_entities);
    }

    public static function getObjectByEntityName($entity)
    {
        if (!isset(self::$export_entities[$entity])) {
            throw new PrestaShopException('Invalid export entity.');
        }

        return self::$export_entities[$entity];
    }
}
