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

function upgrade_module_4_3_7($module)
{
    $upgrade_version = '4.3.7';

    $module->upgrade_detail[$upgrade_version] = array();

    $attachments = $module->DbExecute(
        'INSERT INTO `'._DB_PREFIX_.'advancedexportfield` (`tab`, `name`, `field`,
        `table`, `alias`, `as`, `attribute`, `return`, `import`, `import_name`, `import_combination`, 
        `import_combination_name`, `isCustom`)
        VALUES ("products", "Product attachments url", "attachments", "other", "", "", 0, "", 0, "", 0, "", 0)'
    );

    $total_product_weight = $module->DbExecute(
        'INSERT INTO `'._DB_PREFIX_.'advancedexportfield` (`tab`, `name`,
        `field`, `table`, `alias`, `as`, `attribute`, `return`, `import`, `import_name`, `import_combination`, 
        `import_combination_name`, `isCustom`)
        VALUES ("orders", "Total product weight", "total_product_weight", "other", "", "", 0, "", 0, "", 0, "", 0)'
    );

    $invoice_state = $module->DbExecute(
        "INSERT INTO `"._DB_PREFIX_."advancedexportfield` (`tab`, `name`, `field`, `table`,
        `alias`, `as`, `attribute`, `return`, `import`, `import_name`, `import_combination`, 
        `import_combination_name`, `isCustom`)
        VALUES ('orders', 'Invoice state', 'invoicestate_name', 'state', 'inv_s', '1', 0, '', 0, '', 0, '', 0)"
    );

    $invoice_country = $module->DbExecute(
        "INSERT INTO `"._DB_PREFIX_."advancedexportfield` (`tab`, `name`, `field`, `table`,
        `alias`, `as`, `attribute`, `return`, `import`, `import_name`, `import_combination`, 
        `import_combination_name`, `isCustom`)
        VALUES ('orders', 'Invoice country', 'invoicecountry_name', 'country_lang', 
        'inv_cl', '1', 0, '', 0, '', 0, '', 0)"
    );

    if (!$attachments or !$total_product_weight or !$invoice_state or !$invoice_country) {
        $module->upgrade_detail[$upgrade_version][] =
        $module->l(sprintf('Can not insert new fields'));
    }

    return (bool) !count($module->upgrade_detail[$upgrade_version]);
}
