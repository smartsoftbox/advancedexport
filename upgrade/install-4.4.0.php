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

function upgrade_module_4_4_0($module)
{
    $upgrade_version = '4.4.0';

    $module->upgrade_detail[$upgrade_version] = array();

    // remove all configuration total and current
    if (!Configuration::deleteByName('AdvancedExport_CURRENT')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t delete AdvancedExport_CURRENT'));
    }
    if (!Configuration::deleteByName('AdvancedExport_TOTAL')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t delete AdvancedExport_TOTAL'));
    }

    // insert new columns into table advancedexport
    if (!UpgradeHelper::insertColumn('ftp_directory', 'ftp_user_pass', 'advancedexport')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t insert ftp_directory'));
    }
    if (!UpgradeHelper::insertColumn('ftp_port', 'ftp_directory', 'advancedexport')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t insert ftp_port'));
    }

    // insert new columns into table advancedexportfield
    if (!UpgradeHelper::insertColumn('group15', 'isCustom', 'advancedexportfield')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t insert group15'));
    }
    if (!UpgradeHelper::insertColumn('group17', 'group15', 'advancedexportfield')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t insert group17'));
    }
    if (!UpgradeHelper::insertColumn('version', 'group17', 'advancedexportfield')) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can\'t insert version'));
    }

    // clean advancedexportfield
    $table_name = _DB_PREFIX_.'advancedexportfield';

    $query = 'DELETE FROM `'.$table_name.'` WHERE isCustom = 0';


    if (!Db::getInstance()->execute($query)) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t clean table advancedexportfield');
    }

    foreach ($module->export_types as $export_type) {
        foreach ($module->$export_type as $field) {
            $field['table'] = $field['database'];
            $field['tab'] = $export_type;
            unset($field['database']);
            if (!UpgradeHelper::insertField($field, 'advancedexportfield')) {
                $module->upgrade_detail[$upgrade_version][] =
                    $module->l(sprintf('Can\'t insert field ' . $field['field']));
            }
        }
    }

    // create cron table
    $table_name = _DB_PREFIX_.'advancedexportcron';

    $query = 'CREATE TABLE IF NOT EXISTS `'.$table_name.'` (
			`id_advancedexportcron` int(10) unsigned NOT NULL auto_increment,
			`id_advancedexport` int(10) NOT NULL,
			`type` varchar(255) NOT NULL,
			`name` varchar(255) NOT NULL,
			`cron_hour` varchar(255) NOT NULL,
			`cron_day` varchar(255) NOT NULL,
			`cron_week` varchar(255) NOT NULL,
			`cron_month` varchar(255) NOT NULL,
			`last_export` varchar(255) NOT NULL,
            `active` BOOL NOT NULL DEFAULT 0,
			PRIMARY KEY  (`id_advancedexportcron`)
			) ENGINE=' ._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


    if (!Db::getInstance()->execute($query)) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l('Can\'t create table advancedexportcron');
    }


    return (bool) !count($module->upgrade_detail[$upgrade_version]);
}

function findKeyByValue($array, $field, $value)
{
    foreach($array as $key => $subarray)
    {
        if ( $subarray[$field] === $value )
            return $key;
    }
    return false;
}
