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
require_once dirname(__FILE__) . '/../classes/Group/OrderGroup.php';

function upgrade_module_4_5_28($module)
{
    $upgrade_version = '4.5.28';

    $module->upgrade_detail[$upgrade_version] = array();

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'orders',
        'payments',
        'advancedexportfield'
    )) {
        if (!DB::getInstance()->execute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('orders', 'Payments', 
            'payments', 'other', '', 0, 0, '', 0, '', 0, '', 0, '" .
            OrderGroup::PAYMENT . "', '', '');"
        )) {
            $module->upgrade_detail[$upgrade_version][] =
                $module->l('Can not insert payments field');
        }
    }

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'orders',
        'transaction_id',
        'advancedexportfield'
    )) {
        $query = 'DELETE FROM `' . _DB_PREFIX_ . 'advancedexportfield` WHERE field = "transaction_id"';

        if (!Db::getInstance()->execute($query)) {
            $module->upgrade_detail[$upgrade_version][] =
                $module->l('Can\'t delete transaction id field');
        }
    }
    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}
