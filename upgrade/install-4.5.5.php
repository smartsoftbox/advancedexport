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


function upgrade_module_4_5_5($module)
{
    $upgrade_version = '4.5.5';

    $module->upgrade_detail[$upgrade_version] = array();

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'products',
        'mpn',
        'advancedexportfield'
    )) {
        if (!DB::getInstance()->execute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('products', 'MPN', 
            'mpn', 'products', 'p', '', 0, '', 20, 'MPN', 0, '', 0, '" .
            ProductGroup::INFORMATION . "', '', '1.7.7')"
        )) {
            $module->upgrade_detail[$upgrade_version][] =
                $module->l('Can not insert mpn field');
        }
    }

    if (!UpgradeHelper::isColumnAndTabWithValueExists(
        'field',
        'products',
        'combination_mpn',
        'advancedexportfield'
    )) {
        if (!DB::getInstance()->execute(
            "INSERT INTO `" . _DB_PREFIX_ . "advancedexportfield` (`tab`, `name`, `field`, `table`, `alias`, `as`, 
            `attribute`, `return`, `import`, `import_name`, `import_combination`, `import_combination_name`, 
            `isCustom`, `group15`, `group17`, `version`) VALUES ('products', 'Combination MPN', 
            'combination_mpn', '', '', '', 1, '', 0, '', 9, 'MPN', 0, '" .
            ProductGroup::COMBINATIONS . "', '', '1.7.7')"
        )) {
            $module->upgrade_detail[$upgrade_version][] =
                $module->l('Can not insert combination mpn field');
        }
    }

    if (!Install::createImportTable()) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can not install %s table', 'advancedexportimport');
    }

    // insert new columns into table advancedexportcron
    if (!UpgradeHelper::insertColumn('is_import', 'last_export', 'advancedexportcron', 'BOOL')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t insert is_import');
    }

    // rename column id_advancedexport to id_model in table advancedexportcron
    if (!UpgradeHelper::renameColumn(
        'id_advancedexport',
        'id_model',
        'advancedexportcron',
        'INT NOT NULL'
    )) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t rename id_advancedexport to id_model');
    }

    // remove files
    // FTP.php
    if (!UpgradeHelper::removeFile(dirname(__FILE__) . '/../classes/FTP.php')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t remove classes/FTP.php');
    }
    // SFTP.php
    if (!UpgradeHelper::removeFile(dirname(__FILE__) . '/../classes/SFTP.php')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t remove classes/SFTP.php');
    }
    // FtpInterface.php
    if (!UpgradeHelper::removeFile(dirname(__FILE__) . '/../classes/FtpInterface.php')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t remove classes/FtpInterface.php');
    }

    // copy CustomFields.php
    if (!rename(
        dirname(__FILE__) . '/../classes/CustomFields.php',
        dirname(__FILE__) . '/../classes/Field/CustomFields.php'
    )) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t copy classes/CustomFields.php');
    }

    // delete folders
    if (!Tools::deleteDirectory(dirname(__FILE__) . '/../views/templates/admin/_configure')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t delete _configure');
    }


    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `import_name` = 'Available for order (0 = No 1 = Yes)' 
        WHERE `field` = 'available_for_order' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update available_for_order');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `import_name` = 'Titles ID (Mr = 1 Ms = 2 else 0)' 
        WHERE `field` = 'id_gender' 
        AND `tab` = 'customers'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update id_gender');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `import_name` = 'Groups (x y z...)' 
        WHERE `field` = 'groups' 
        AND `tab` = 'customers'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update groups');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7' 
        WHERE `field` = 'delivery_in_stock' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update delivery_in_stock');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7' 
        WHERE `field` = 'delivery_out_stock' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update delivery_out_stock');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7' 
        WHERE `field` = 'low_stock_threshold' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update low_stock_threshold');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7.7' 
        WHERE `field` = 'mpn' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update mpn');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7.7' 
        WHERE `field` = 'combination_mpn' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update combination_mpn');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7' 
        WHERE `field` = 'combination_low_stock_threshold' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update combination_low_stock_threshold');
    }

    if (!Db::getInstance()->execute("UPDATE  `" . _DB_PREFIX_ . "advancedexportfield` 
        SET `version` = '1.7' 
        WHERE `field` = 'combination_low_stock_alert' 
        AND `tab` = 'products'")) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t update combination_low_stock_alert');
    }


    if (!Install::installTabs()) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can not inserts tabs');
    }

    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}
