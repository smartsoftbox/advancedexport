<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
use Context;
use UpgradeHelper;
use Configuration;
use Tools;
use Db;
use AdvancedExportClass;
use AdvancedExportCronClass;
use AdvancedExportFieldClass;
use AdvancedExportImportClass;
use AddressesFields;
use CategoriesFields;
use CustomersFields;
use ManufacturersFields;
use NewslettersFields;
use OrdersFields;
use ProductsFields;
use SuppliersFields;

require_once dirname(__FILE__) . '/../../advancedexport.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportCronClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportImportClass.php';

require_once dirname(__FILE__) . '/../../classes/Field/AddressesFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/CategoriesFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/CustomersFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/ManufacturersFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/NewslettersFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/OrdersFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/ProductsFields.php';
require_once dirname(__FILE__) . '/../../classes/Field/SuppliersFields.php';

class Install455Test extends IntegrationTestCase
{
    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../config/config.inc.php';
        Context::getContext()->employee = new \Employee(1);
    }

    public function test_Advancedexport_Table()
    {
        //arrange
        $table_name = 'advancedexport';
        $ae = new \AdvancedExportClass();
        $fields = $ae->getFields();

        //assert
        foreach ($fields as $key => $field) {
            $this->assertTrue(
                $this->isColumnExists($key, $table_name),
                'Column ' . $key . ' does not exist'
            );
        }

        $this->assertEquals(28, $this->getNumberOfColumns($table_name));
    }

    public function test_AdvancedexportCron_Table()
    {
        //arrange
        $table_name = 'advancedexportcron';
        $aeCron = new \AdvancedExportCronClass(1);
        $fields = $aeCron->getFields();

        //assert
        foreach ($fields as $key => $field) {
            $this->assertTrue(
                $this->isColumnExists($key, $table_name),
                'Column ' . $key . ' does not exist'
            );
        }

        $this->assertEquals(11, $this->getNumberOfColumns($table_name));
    }

    public function test_AdvancedexportField_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $ae = new \AdvancedExportFieldClass();
        $fields = $ae->getFields();

        //assert
        foreach ($fields as $key => $field) {
            $this->assertTrue(
                $this->isColumnExists($key, $table_name),
                'Column ' . $key . ' does not exist'
            );
        }

        $this->assertEquals(17, $this->getNumberOfColumns($table_name));
    }

    public function test_AdvancedexportImport_Table()
    {
        //arrange
        $table_name = 'advancedexportimport';
        $ae = new \AdvancedExportImportClass();
        $fields = $ae->getAllFields();

        //assert
        foreach ($fields as $key => $field) {
            $this->assertTrue(
                $this->isColumnExists($key, $table_name),
                'Column ' . $key . ' does not exist'
            );
        }

        $this->assertEquals(24, $this->getNumberOfColumns($table_name));
    }

    public function test_AdvancedexportField_Check_ALL_Addresses_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'addresses';
        $addresses_fields =  new AddressesFields();
        $fields = $addresses_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(21, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Categories_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'categories';
        $categories_fields =  new CategoriesFields();
        $fields = $categories_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(18, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Customers_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'customers';
        $customers_fields =  new CustomersFields();
        $fields = $customers_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(33, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Manufacturers_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'manufacturers';
        $manufacturers_fields =  new ManufacturersFields();
        $fields = $manufacturers_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(10, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Newsletter_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'newsletters';
        $newsletter_fields =  new NewslettersFields();
        $fields = $newsletter_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(5, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Orders_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'orders';
        $orders_fields =  new OrdersFields();
        $fields = $orders_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(99, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Products_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'products';
        $products_fields =  new ProductsFields();
        $fields = $products_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(119, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function test_AdvancedexportField_Check_ALL_Suppliers_Fields_Exists_In_Table()
    {
        //arrange
        $table_name = 'advancedexportfield';
        $tab = 'suppliers';
        $suppliers_fields =  new SuppliersFields();
        $fields = $suppliers_fields->fields;

        //assert
        $this->checkFieldsExists($fields, $tab, $table_name);
        $this->assertEquals(9, $this->getNumberOfRowsWithGivenType($table_name, $tab));
    }

    public function teardown()
    {
    }

    public function isColumnExists($column, $table)
    {
        Db::getInstance()->executeS("SHOW COLUMNS FROM `" . _DB_PREFIX_ . pSQL($table) . "`
         LIKE '" . pSQL($column) . "'");
        return (DB::getInstance()->numRows() ? true : false);
    }

    public function isColumnWithValueExists($column, $value, $table)
    {
        Db::getInstance()->executeS("SELECT `" . $column . "`  
        FROM `" . _DB_PREFIX_ . pSQL($table) . "` WHERE `" . $column . "` = '" . pSQL($value) . "'");

        return (DB::getInstance()->numRows() ? true : false);
    }

    public function isColumnAndTabWithValueExists($column, $tab, $value, $table)
    {
        Db::getInstance()->executeS("SELECT `" . $column . "`  
        FROM `" . _DB_PREFIX_ . $table . "` WHERE `" . $column . "` = '" . pSQL($value) . "' 
        AND `tab` = '" . $tab . "'");

        return (DB::getInstance()->numRows() ? true : false);
    }

    public function getNumberOfColumns($table)
    {
        $column_number = Db::getInstance()->getValue("SELECT count(*)
        FROM information_schema.columns
        WHERE table_name  = '" . _DB_PREFIX_ . $table . "'");

        return $column_number;
    }

    public function getNumberOfRowsWithGivenType($table, $type)
    {
        $rows_number = Db::getInstance()->getValue("SELECT count(*)
        FROM `" . _DB_PREFIX_ . $table . "` WHERE tab = '" . $type . "'");

        return $rows_number;
    }

    public function renameArrayKey($arr, $old_key, $new_key)
    {
        $arr[$new_key] = $arr[$old_key];
        unset($arr[$old_key]);

        return $arr;
    }

    /**
     * @param array $fields
     * @param $tab
     * @param $table_name
     */
    public function checkFieldsExists(array $fields, $tab, $table_name)
    {
        foreach ($fields as $key => $field) {
            $field = $this->renameArrayKey($field, 'database', 'table');
            $this->checkColumnsAndTabExists($tab, $table_name, $field, $key);
        }
    }

    /**
     * @param $tab
     * @param $table_name
     * @param $field
     * @param $key
     */
    public function checkColumnsAndTabExists($tab, $table_name, $field, $key)
    {
        foreach ($field as $column => $value) {
            $this->assertTrue(
                $this->isColumnAndTabWithValueExists($column, $tab, $value, $table_name),
                'Field ' . $field['field'] . ' with column: ' . $column . ' with value: ' .
                $value . ' does not exist'
            );
        }
    }
}
