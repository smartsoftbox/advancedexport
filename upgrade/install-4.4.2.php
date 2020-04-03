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

function upgrade_module_4_4_2($module)
{
    $upgrade_version = '4.4.2';

    $module->upgrade_detail[$upgrade_version] = array();

    // insert new columns into table advancedexport
    if (!UpgradeHelper::insertColumn('file_format', 'filename', 'advancedexport')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t insert file_format'));
    }

    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}
