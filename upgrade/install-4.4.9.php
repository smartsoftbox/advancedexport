<?php
/**
 * 2019 Smart Soft.
 *
 *  @author    Marcin Kubiak <zlecenie@poczta.onet.pl>
 *  @copyright Smart Soft
 *  @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/UpgradeHelper.php';
require_once dirname(__FILE__) . '/../classes/Group/ProductGroup.php';
require_once dirname(__FILE__) . '/../classes/Group/OrderGroup.php';

function upgrade_module_4_4_9($module)
{
    $upgrade_version = '4.4.9';

    $module->upgrade_detail[$upgrade_version] = array();

    //insert id product attribute
    $id_product_attribute = true;

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'products',
        'combination_id_product_attribute',
        'advancedexportfield'
    )) {
        $id_product_attribute = $module->DbExecute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('products', 'Combination Id Product Attribute', 
            'combination_id_product_attribute', '', '', '', 1, '', 0, '', 0, '', 0, '" .
            ProductGroup::COMBINATIONS . "', '', '')"
        );
    }

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'orders',
        'invoice_vat_number',
        'advancedexportfield'
    )) {
        $invoice_vat_number = $module->DbExecute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('orders', 'Invoice VAT', 
            'invoice_vat_number', 'address', 'inv_a', '1', 0, '', 0, '', 0, '', 0, '" .
            OrderGroup::INVOICE . "', '', '');"
        );
    }

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'orders',
        'invoice_dni',
        'advancedexportfield'
    )) {
        $invoice_dni = $module->DbExecute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('orders', 'Invoice DNI', 
            'invoice_dni', 'address', 'inv_a', '1', 0, '', 0, '', 0, '', 0, '" .
            OrderGroup::INVOICE . "', '', '');"
        );
    }

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'orders',
        'invoicecountry_iso_code',
        'advancedexportfield'
    )) {
        $invoicecountry_iso_code = $module->DbExecute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('orders', 'Invoice country iso', 
            'invoicecountry_iso_code', 'country', 'inv_co', '1', 0, '', 0, '', 0, '', 0, '" .
            OrderGroup::INVOICE . "', '', '');"
        );
    }

    if (!$id_product_attribute && !$invoice_dni && !$invoice_vat_number && !$invoicecountry_iso_code) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can not insert id_product_attribute fields'));
    }

    return (bool) !count($module->upgrade_detail[$upgrade_version]);
}
