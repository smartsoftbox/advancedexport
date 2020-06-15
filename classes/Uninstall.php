<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class Uninstall
{
    public function run()
    {
        $tables = array('advancedexport', 'advancedexportfield', 'advancedexportcron', 'advancedexportimport');

        if (!self::dropTables($tables)
            or !self::uninstallTab(_ADMIN_AE_)

        ) {
            return false;
        }

        if (_PS_VERSION_ >= 1.5 && _PS_VERSION_ < 1.7) {
            if (!self::uninstallTab(_ADMIN_AE_MODEL_)
                or !self::uninstallTab(_ADMIN_AE_CRON_)
                or !self::uninstallTab(_ADMIN_AE_MODEL_FIELD_)
                or !self::uninstallTab(_ADMIN_AE_MODEL_FILE_)
                or !self::uninstallTab(_ADMIN_AE_IMPORT_)
                or !self::uninstallTab(_ADMIN_AE_IMPORT_FILE_)
            ) {
                return false;
            }
        }

        if (_PS_VERSION_ >= 1.7) {
            if (!self::dropTables(array('advancedexportimport'))) {
                return false;
            }
        }

        return true;
    }

    public static function uninstallTab($class_name)
    {
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);

        return $tab->delete();
    }

    public static function dropTables($tables)
    {
        if (!is_array($tables)) {
            $tables = array($tables);
        }

        foreach ($tables as $table) {
            if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $table . '`')) {
                return false;
            }
        }

        return true;
    }
}
