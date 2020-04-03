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

function upgrade_module_4_5_1($module)
{
    $upgrade_version = '4.5.1';

    $module->upgrade_detail[$upgrade_version] = array();

    //insert id product attribute
    $invoicestate_iso_code = true;

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'orders',
        'invoicestate_iso_code',
        'advancedexportfield'
    )) {
        $invoicestate_iso_code = DB::getInstance()->execute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('orders', 'Invoice state iso code', 
            'invoicestate_iso_code', 'state', 'inv_s', '1', 0, '', 0, '', 0, '', 0, '" .
            OrderGroup::INVOICE . "', '', '');"
        );
    }

    if (!$invoicestate_iso_code) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can not insert fields'));
    }

    return (bool) !count($module->upgrade_detail[$upgrade_version]);
}
