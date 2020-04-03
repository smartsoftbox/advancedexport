<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak <zlecenie@poczta.onet.pl>
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/Field418.php';
require_once dirname(__FILE__) . '/UpgradeHelper.php';

function upgrade_module_4_3_0($module)
{
    $upgrade_version = '4.3.0';

    $module->upgrade_detail[$upgrade_version] = array();

    $table_name = _DB_PREFIX_ . 'advancedexportfield';
    DB::getInstance()->execute('DROP TABLE IF EXISTS `' . $table_name . '`');

    $query = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
        `id_advancedexportfield` int(10) unsigned NOT NULL auto_increment,
        `tab` varchar(255) NOT NULL,
        `name` varchar(255) NOT NULL,
        `field` varchar(255) NOT NULL,
        `table` varchar(255) NOT NULL,
        `alias` varchar(255) NOT NULL,
        `as` varchar(255) NOT NULL,
        `attribute` BOOL NOT NULL DEFAULT 0,
        `return` varchar(255) NOT NULL,
        `import` BOOL NOT NULL DEFAULT 0,
        `import_name` varchar(255) NOT NULL,
        `import_combination` BOOL NOT NULL DEFAULT 0,
        `import_combination_name` varchar(255) NOT NULL,
        `isCustom` BOOL NOT NULL DEFAULT 0,
        PRIMARY KEY  (`id_advancedexportfield`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

    if (!DB::getInstance()->execute($query)) {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('Can not install %s table', _DB_PREFIX_ . 'advancedexportfield'));
    }

    //insert fields
    $field418 = new Field418();

    foreach ($field418->export_types as $tab) {
        foreach ($field418->$tab as $item) {
            $item['table'] = $item['database'];
            $item['tab'] = $tab;
            unset($item['database']);
            UpgradeHelper::insertField($item, 'advancedexportfield');
        }
    }


    $version = DB::getInstance()->executeS(
        'SELECT version FROM `' . _DB_PREFIX_ . 'module` WHERE name = "advancedexport"'
    );

    if ('4.2.0' == $version[0]['version']) {
        $models = DB::getInstance()->executeS('select * from ' . _DB_PREFIX_ . 'advancedexport');

        foreach ($models as $model) {
            if (checkIfModelContainsCustomFields($model)) {
                $module->upgrade_detail[$upgrade_version][] =
                    $module->l(sprintf('Wrong field id. Please contact support.'));
            }
        }
        if (count($module->upgrade_detail[$upgrade_version]) == 0) {
            $all = DB::getInstance()->executeS(
                sprintf("SELECT * FROM `%s`", _DB_PREFIX_ . 'advancedexportfield')
            );

            $allFields = array();
            foreach ($all as $field) {
                $allFields[$field['tab']][] = $field;
            }

            foreach ($models as $model) {
                $namesFields = changeIdsToFieldNames($model, $allFields);

                DB::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'advancedexport`
                    SET fields = "' . pSQL($namesFields) .
                    '" WHERE id_advancedexport = ' . $model['id_advancedexport']);
            }
        }
    } else {
        $module->upgrade_detail[$upgrade_version][] =
            $module->l(sprintf('You can upgrade only from version 4.2.0'));
    }

    return (bool)!count($module->upgrade_detail[$upgrade_version]);
}

/**
 * @param $model
 * @return bool
 */
function checkIfModelContainsCustomFields($model)
{
    $fields = Tools::jsonDecode($model['fields'], true);

    foreach ($fields['fields[]'] as $field) {
        if ($model['type'] == 'products' && $field > 63
            || $model['type'] == 'orders' && $field > 86
            || $model['type'] == 'categories' && $field > 16
            || $model['type'] == 'manufacturers' && $field > 8
            || $model['type'] == 'suppliers' && $field > 7
            || $model['type'] == 'customers' && $field > 26
            || $model['type'] == 'newsletters' && $field > 4) {
            return true;
        }
    }

    return false;
}

/**
 * @param $model
 * @param $allFields
 * @return string
 */
function changeIdsToFieldNames($model, $allFields)
{

    $fields = Tools::jsonDecode($model['fields'], true);
    $fieldNames = array();
    foreach ($fields['fields[]'] as $field) {
        $fieldNames[] = $allFields[$model['type']][$field]['field'];
    }

    $fields['fields[]'] = $fieldNames;

    return Tools::jsonEncode($fields, true);
}
