<?php
/**
 * 2019 Smart Soft.
 *
 *  @author    Marcin Kubiak <zlecenie@poczta.onet.pl>
 *  @copyright Smart Soft
 *  @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class UpgradeHelper
{
    public static function isColumnExists($column, $table)
    {
        Db::getInstance()->executeS("SHOW COLUMNS FROM `" . _DB_PREFIX_.pSQL($table) . "`
         LIKE '" . pSQL($column) . "'");
        return (DB::getInstance()->numRows() ? true : false);
    }

    public static function insertColumn($column, $after, $table, $type = 'varchar(255)')
    {
        if (self::isColumnExists($column, $table)) {
            return true;
        }

        $query = 'ALTER TABLE `'._DB_PREFIX_.$table.'` 
        ADD `' . $column . '` ' . $type . ' NOT NULL DEFAULT 0 after `' . $after . '`';
        return Db::getInstance()->execute($query);
    }

    public static function isColumnWithValueExists($column, $value, $table)
    {
        Db::getInstance()->executeS("SELECT `" . $column . "`  
        FROM `" . _DB_PREFIX_.pSQL($table) . "` WHERE `" . $column ."` = '" . pSQL($value) . "'");

        return (DB::getInstance()->numRows() ? true : false);
    }

    public static function isColumnAndTabWithValueExists($column, $tab, $value, $table)
    {
        Db::getInstance()->executeS("SELECT `" . $column . "`  
        FROM `" . _DB_PREFIX_.$table . "` WHERE `" . $column ."` = '" . pSQL($value) . "' 
        AND `tab` = '" . $tab . "'");

        return (DB::getInstance()->numRows() ? true : false);
    }

    public static function isTableExists($table)
    {
        Db::getInstance()->executeS("SHOW TABLES LIKE '"._DB_PREFIX_.$table."'");
        return (DB::getInstance()->numRows() ? true : false);
    }

    public static function insertField($array, $table)
    {
        if (self::isColumnAndTabWithValueExists('field', $array['tab'], $array['field'], $table)) {
            return true;
        }

        $array = array_map(function ($value){
            return pSQL($value);
        }, $array);

        $query = 'INSERT INTO '._DB_PREFIX_.$table.'(`'.implode('`, `', array_keys($array)).'`) 
        VALUES("'.implode('", "', $array).'")';

        return Db::getInstance()->execute($query);
    }

    public static function updateField($array, $table)
    {
        if (!self::isColumnWithValueExists('field', $array['field'], $table)) {
            return true;
        }

        $array = array_map(function ($value){
            return pSQL($value);
        }, $array);

        $result = array();
        foreach ($array as $key => $field) {
            $result[] = Db::getInstance()->execute('UPDATE '._DB_PREFIX_.$table.'
            SET `' . $key . '` = "' . $field . '"
            WHERE tab = "' . $array['tab'] . '" 
            AND field = "' . $array['field'] . '"');
        }

        return (in_array(false, $result) ? false : true);
    }
}
