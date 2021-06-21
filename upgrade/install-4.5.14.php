<?php
/**
 * 2019 Smart Soft.
 *
 * @author    Marcin Kubiak <zlecenie@poczta.onet.pl>
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/UpgradeHelper.php';
require_once dirname(__FILE__) . '/../classes/Install.php';
require_once dirname(__FILE__) . '/../classes/Group/ProductGroup.php';


function upgrade_module_4_5_14($module)
{
    $upgrade_version = '4.5.14';

    $module->upgrade_detail[$upgrade_version] = array();

    $fields = array(
        'price',
        'wholesale_price',
        'id_tax_rules_group',
        'on_sale',
        'date_add',
        'data_upd',
        'active',
        'online_only',
        'ecotax',
        'unity',
        'unit_price_ratio',
        'minimal_quantity',
        'additional_shipping_cost',
        'customizable',
        'uploadable_files',
        'text_fields',
        'available_for_order',
        'condition',
        'show_price',
        'indexed',
        'cache_default_attribute',
        'visibility',
        'available_date',
        'advanced_stock_management'
    );

    foreach ($fields as $field) {
        if (! Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'advancedexportfield
            SET `table` = "product_shop", `alias` = "product_shop"
            WHERE tab = "products" AND field = "' . $field . '"')
        ) {
            $module->upgrade_detail[$upgrade_version][] = $module->l('Can not update field name: ') . $field;
        }
    }

    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}
