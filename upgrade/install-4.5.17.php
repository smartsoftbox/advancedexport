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


function upgrade_module_4_5_17($module)
{
    $upgrade_version = '4.5.17';

    $module->upgrade_detail[$upgrade_version] = array();

    // insert new columns into table advancedexport
    if (!UpgradeHelper::insertColumn('file_no_data', 'only_new', 'advancedexport', 'BOOL')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t insert file no data column to advancedexport');
    }

    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}
