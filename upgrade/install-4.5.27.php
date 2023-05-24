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

function upgrade_module_4_5_27($module)
{
    $upgrade_version = '4.5.27';

    $module->upgrade_detail[$upgrade_version] = array();

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'products',
        'isbn',
        'advancedexportfield'
    )) {
        if (!DB::getInstance()->execute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('products', 'ISBN', 
            'isbn', 'products', 'p', 0, 0, '', 0, '', 0, '', 0, '" .
            ProductGroup::INFORMATION . "', '', '');"
        )) {
            $module->upgrade_detail[$upgrade_version][] =
                $module->l('Can not insert ISBN field');
        }
    }

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'products',
        'combination_isbn',
        'advancedexportfield'
    )) {
        if (!DB::getInstance()->execute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('products', 'Combination ISBN', 
            'combination_isbn', '', '', 0, 1, '', 0, '', 0, '', 0, '" .
            ProductGroup::COMBINATIONS . "', '', '');"
        )) {
            $module->upgrade_detail[$upgrade_version][] =
                $module->l('Can not insert Combination ISBN field');
        }
    }

    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}
