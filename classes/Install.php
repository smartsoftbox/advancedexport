<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once 'Data/ExportEnum.php';
require_once 'Field/ProductsFields.php';
require_once 'Field/AddressesFields.php';
require_once 'Field/CategoriesFields.php';
require_once 'Field/CustomersFields.php';
require_once 'Field/ManufacturersFields.php';
require_once 'Field/NewslettersFields.php';
require_once 'Field/OrdersFields.php';
require_once 'Field/ProductsFields.php';
require_once 'Field/SuppliersFields.php';

class Install
{
    public static function run()
    {
        if (!self::createFieldTable()
            or !self::createSettingsTables()
            or !self::createCronTable()
            or !Configuration::updateGlobalValue(
                'ADVANCEDEXPORT_SECURE_KEY',
                Tools::strtoupper(Tools::passwdGen(16))
            )) {
            return false;
        }


        if (!self::createImportTable()) {
            return false;
        }

        if (!self::installTabs()) {
            return false;
        }

        return true;
    }

    public static function installTabs()
    {
        if (!$parent = self::installTab(
            (int)Tab::getIdFromClassName('AdminTools'),
            _ADMIN_AE_,
            'Import & Export'
        )) {
            return false;
        }

        $parent = -1;

        if (!self::installTab($parent, _ADMIN_AE_MODEL_, 'Export Model')
            or !self::installTab($parent, _ADMIN_AE_CRON_, 'Cron Tasks')
            or !self::installTab($parent, _ADMIN_AE_MODEL_FIELD_, 'Export Field')
            or !self::installTab($parent, _ADMIN_AE_MODEL_FILE_, 'Export Files')
        ) {
            return false;
        }

        if (_PS_VERSION_ >= 1.7) {
            if (!self::installTab($parent, _ADMIN_AE_IMPORT_, 'Import Model')
                or !self::installTab($parent, _ADMIN_AE_IMPORT_FILE_, 'Import Files')
            ) {
                return false;
            }
        }

        return true;
    }

    public static function installTab($parent, $class_name, $name)
    {
        $tab = new Tab();
        $tab->id_parent = $parent;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        $tab->class_name = $class_name;
        $tab->module = 'advancedexport';
        $tab->active = 1;
        $tab->add();

        return $tab->id;
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public static function createFieldTable()
    {
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advancedexportfield` (
			`id_advancedexportfield` int(10) unsigned NOT NULL auto_increment,
			`tab` varchar(255) NOT NULL,
			`name` varchar(255) NOT NULL,
			`field` varchar(255) NOT NULL,
			`table` varchar(255) NOT NULL,
			`alias` varchar(255) NOT NULL,
			`as` varchar(255) NOT NULL,
			`attribute` BOOL NOT NULL DEFAULT 0,
			`return` varchar(255) NOT NULL,
			`import` int(10) unsigned NOT NULL,
			`import_name` varchar(255) NOT NULL,
			`import_combination` int(10) unsigned NOT NULL,
			`import_combination_name` varchar(255) NOT NULL,
			`isCustom` BOOL NOT NULL DEFAULT 0,
			`group15` varchar(255) NOT NULL,
			`group17` varchar(255) NOT NULL,
			`version` varchar(255) NOT NULL,
			PRIMARY KEY  (`id_advancedexportfield`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        if (!Db::getInstance()->Execute($query)) {
            return false;
        }

        self::addFieldsToTable();
        self::installCustomFields();

        return true;
    }

    /**
     * @throws PrestaShopException
     */
    public static function addFieldsToTable()
    {
        $export_types = ExportEnum::getExportEntities();

        foreach ($export_types as $tab) {
            $field_class = Tools::ucfirst($tab) . 'Fields';
            $field = new $field_class();
            foreach ($field->getFields() as $item) {
                if (!isset($item['version']) || isset($item['version']) && _PS_VERSION_ >= $item['version']) {
                    self::saveField($tab, $item);
                }
            }
        }
    }

    /**
     * @param $tab
     * @param $item
     * @return mixed
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function saveField($tab, $item, $custom = false)
    {
        $field = new AdvancedExportFieldClass();
        $field->tab = $tab;
        $field->name = $item['name'];
        $field->field = $item['field'];
        $field->table = $item['database'];
        $field->alias = (isset($item['alias']) ? $item['alias'] : '');
        $field->as = (isset($item['as']) ? $item['as'] : false);
        $field->attribute = (isset($item['attribute']) ? $item['attribute'] : false);
        $field->import = (isset($item['import']) ? $item['import'] : false);
        $field->import_name = (isset($item['import_name']) ? $item['import_name'] : '');
        $field->import_combination =
            (isset($item['import_combination']) ? $item['import_combination'] : false);
        $field->import_combination_name =
            (isset($item['import_combination_name']) ? $item['import_combination_name'] : '');
        $field->isCustom = $custom;
        $field->group15 = (isset($item['group15']) ? $item['group15'] : '');
        $field->group17 = (isset($item['group17']) ? $item['group17'] : '');
        $field->version = (isset($item['version']) ? $item['version'] : '');
        $field->add();

        return $item;
    }

    public static function createSettingsTables()
    {
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advancedexport` (
			`id_advancedexport` int(10) unsigned NOT NULL auto_increment,
			`type` varchar(200) NOT NULL,
			`name` varchar(200) NOT NULL,
			`delimiter` varchar(255) NOT NULL,
			`separator` varchar(255) NOT NULL,
			`id_lang` int(10) NOT NULL,
			`charset` varchar(255) NOT NULL,
			`add_header` BOOL NOT NULL DEFAULT 0,
			`decimal_separator` varchar(10) NOT NULL,
			`decimal_round` int(10) NOT NULL,
			`strip_tags` BOOL NOT NULL DEFAULT 0,
			`only_new` BOOL NOT NULL DEFAULT 0,
			`date_from` varchar(255) NOT NULL,
			`date_to` varchar(255) NOT NULL,
            `last_exported_id` int(10) NOT NULL DEFAULT 0,
            `start_id` int(10) NOT NULL DEFAULT 0,
			`end_id` int(10) NOT NULL DEFAULT 0,
			`save_type` int(10) NOT NULL DEFAULT 0,
			`filename` varchar(255) NOT NULL,
			`file_format` varchar(255) NOT NULL,
			`image_type` varchar(255) NOT NULL,
			`email` varchar(255) NOT NULL,
			`ftp_hostname` varchar(255) NOT NULL,
			`ftp_user_name` varchar(255) NOT NULL,
			`ftp_user_pass` varchar(255) NOT NULL,
			`ftp_directory` varchar(255) NOT NULL,
			`ftp_port` varchar(255) NOT NULL,
			`fields` text  NOT NULL,
			PRIMARY KEY  (`id_advancedexport`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        if (!Db::getInstance()->Execute($query)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public static function createCronTable()
    {
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advancedexportcron` (
			`id_advancedexportcron` int(10) unsigned NOT NULL auto_increment,
			`id_model` int(10) NOT NULL,
			`type` varchar(255) NOT NULL,
			`name` varchar(255) NOT NULL,
			`cron_hour` varchar(255) NOT NULL,
			`cron_day` varchar(255) NOT NULL,
			`cron_week` varchar(255) NOT NULL,
			`cron_month` varchar(255) NOT NULL,
			`last_export` varchar(255) NOT NULL,
			`is_import` BOOL NOT NULL DEFAULT 0,
            `active` BOOL NOT NULL DEFAULT 0,
			PRIMARY KEY  (`id_advancedexportcron`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        if (!Db::getInstance()->Execute($query)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public static function createImportTable()
    {
        if (_PS_VERSION_ >= 1.7) {
            $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advancedexportimport` (
			`id_advancedexportimport` int(10) unsigned NOT NULL auto_increment,
			`name` varchar(255) NOT NULL,
			`import_from` int(10) unsigned NOT NULL,
			`filename` varchar(255) NOT NULL,
			`import_filename` varchar(255) NOT NULL,
			`file_token` varchar(255) NOT NULL,
			`url` varchar(255) NOT NULL,
			`id_advancedexport` int(10) NOT NULL,
			`ftp_hostname` varchar(255) NOT NULL,
			`ftp_user_name` varchar(255) NOT NULL,
			`ftp_user_pass` varchar(255) NOT NULL,
			`ftp_directory` varchar(255) NOT NULL,
			`ftp_port` varchar(255) NOT NULL,
			`entity` varchar(255) NOT NULL,
			`iso_lang` varchar(2) NOT NULL,
			`separator` varchar(1) NOT NULL,
			`multi_value_separator` varchar(1) NOT NULL,
            `truncate` BOOL NOT NULL DEFAULT 0,
            `regenerate` BOOL NOT NULL DEFAULT 0,
            `match_ref` BOOL NOT NULL DEFAULT 0,
            `forceIDs` BOOL NOT NULL DEFAULT 0,
            `send_email` BOOL NOT NULL DEFAULT 0,
            `skip` int(10) NOT NULL DEFAULT 0,
            `mapping` TEXT NOT NULL,
			PRIMARY KEY  (`id_advancedexportimport`)
			) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

            if (!Db::getInstance()->Execute($query)) {
                return false;
            }
        }

        return true;
    }

    public function dbExecute($query)
    {
        return Db::getInstance()->Execute($query);
    }

    public static function installCustomFields()
    {
        $dir = dirname(__FILE__).'/Field/CustomFields.php';

        if (file_exists($dir)) {
            require_once($dir);

            $customFields = new CustomFields();
            $export_types = ExportEnum::getExportEntities();

            foreach ($export_types as $tab) {
                if (empty($export_types->$tab)) {
                    foreach ($customFields->$tab as $field) {
                        if (!isset($field['version']) || isset($field['version']) &&
                            _PS_VERSION_ >= $field['version']) {
//                    Db::getInstance()->execute("DELETE  FROM `" . _DB_PREFIX_ . "advancedexportfield`
//                    WHERE `field` = '" . pSQL($field['field']) . "'");

                            self::saveField($tab, $field, true);
                        }
                    }
                }
            }
        }
    }
}
