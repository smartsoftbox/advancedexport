<?php
/**
 * 2016 Smart Soft.
 *
 *  @author    Marcin Kubiak <zlecenie@poczta.onet.pl>
 *  @copyright Smart Soft
 *  @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_4_3_8($module)
{
    $upgrade_version = '4.3.8';

    $module->upgrade_detail[$upgrade_version] = array();

    $invoice_other = $module->DbExecute(
        "INSERT INTO `"._DB_PREFIX_."advancedexportfield` (`tab`, `name`, `field`, `table`,
        `alias`, `as`, `attribute`, `return`, `import`, `import_name`, `import_combination`, 
        `import_combination_name`, `isCustom`)
        VALUES ('orders', 'Invoice Other', 'invoice_other', 'address', 'inv_a', '1', 0, '', 0, '', 0, '', 0)"
    );

    $delivery_other = $module->DbExecute(
        "INSERT INTO `"._DB_PREFIX_."advancedexportfield` (`tab`, `name`, `field`, `table`,
        `alias`, `as`, `attribute`, `return`, `import`, `import_name`, `import_combination`, 
        `import_combination_name`, `isCustom`)
        VALUES ('orders', 'Delivery other', 'delivery_other', 'address', 'inv_a', '1', 0, '', 0, '', 0, '', 0)"
    );

    if (!$invoice_other or !$delivery_other) {
        $module->upgrade_detail[$upgrade_version][] =
        $module->l(sprintf('Can not insert new fields'));
    }

    return (bool) !count($module->upgrade_detail[$upgrade_version]);
}
