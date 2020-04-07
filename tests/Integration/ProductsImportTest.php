<?php

namespace LegacyTests\TestCase;

use Employee;
use PrestaShopBundle\Install\DatabaseDump;
use Context;
use AdminAdvancedExportModelController;
use Export;
use AdvancedExportClass;
use Configuration;
use Product;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../controllers/admin/AdminAdvancedExportModelController.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../classes/Field/CustomFields.php';

class ProductsImportTest extends IntegrationTestCase
{
    private static $dump;
    private $adminAdvancedExportModelController;
    private $export;
    private $row;
    private $entity;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../config/config.inc.php';
        Context::getContext()->employee = new Employee(1);
    }

    public static function tearDownAfterClass()
    {
    }

    /**
     * Provide sensible defaults for tests that don't specify them.
     */
    public function setUp()
    {
        parent::setUp();

        $this->entity = 'products';
        $this->adminAdvancedExportModelController = $this->createPartialMock(
        'AdminAdvancedExportModelController',
            array()
        );

        $this->export = new Export();

        // create export models for import
        $advancedExportClass = $this->adminAdvancedExportModelController->generateDefaultCsvForImport($this->entity);
        // run export
        $this->export->createExportFile($advancedExportClass);
        // read files
        $url = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/' . $this->entity . '/' . $this->entity . '_import.csv';

        $rows = array_map('str_getcsv', file($url));
        foreach ($rows[0] as $key => $fieldName) {
            $this->row[$fieldName] = $rows['1'][$key];
        }
    }

    public function test_CsvImportFileData()
    {
        $this->assertSame($this->row['ID'], '1');
        $this->assertSame($this->row['Active (0/1)'], '1');
        $this->assertSame($this->row['Name'], 'Hummingbird printed t-shirt');
        $this->assertSame($this->row['Price tax excluded'], '22.944'); //todo in migration is 19.12
        $this->assertSame($this->row['Tax rule ID'], '1');
        $this->assertSame($this->row['Cost price'], '0.000000');
        $this->assertSame($this->row['On sale (0/1)'], '0');
        $this->assertSame($this->row['Discount amount'], ''); // reduction_price
        $this->assertSame($this->row['Discount percent'], '0.200000'); // reduction_percent
        $this->assertSame($this->row['Discount from (yyyy-mm-dd)'], '0000-00-00 00:00:00'); // reduction_from
        $this->assertSame($this->row['Discount to (yyyy-mm-dd)'], '0000-00-00 00:00:00'); // reduction_to
        $this->assertSame($this->row['Reference #'], 'demo_1');
        $this->assertSame($this->row['Supplier'], '');
        $this->assertSame($this->row['EAN13'], '');
        $this->assertSame($this->row['UPC'], '');
        $this->assertSame($this->row['Ecotax'], '0.000000');
        $this->assertSame($this->row['Width'], '0.000000');
        $this->assertSame($this->row['Height'], '0.000000');
        $this->assertSame($this->row['Depth'], '0.000000');
        $this->assertSame($this->row['Weight'], '0.000000');
        $this->assertSame($this->row['Minimal quantity'], '1');
        $this->assertSame($this->row['Visibility'], 'both');
        $this->assertSame($this->row['Additional shipping cost'], '0.00');
        $this->assertSame($this->row['Unit for base price'], '');
        $this->assertSame($this->row['Base price'], ''); // todo why was 0.000000
        $this->assertNotSame(false, strpos($this->row['Summary'], 'Regular'));
        $this->assertNotSame(false, strpos($this->row['Description'], 'lightness'));
        $this->assertSame($this->row['Meta title'], '');
        $this->assertSame($this->row['Meta keywords'], '');
        $this->assertSame($this->row['Meta description'], '');
        $this->assertSame($this->row['Rewritten URL'], 'hummingbird-printed-t-shirt');
        $this->assertSame($this->row['Label when in stock'], '');
        $this->assertSame($this->row['Label when backorder allowed'], '');
        $this->assertSame($this->row['Available for order (0 = No 1 = Yes)'], '1');
        $this->assertSame($this->row['Product availability date'], '0000-00-00');
        $this->assertSame($this->row['Product creation date'], '2020-03-05 10:34:02');
        $this->assertSame($this->row['Show price (0 = No 1 = Yes)'], '1');
        $this->assertSame($this->row['Available online only (0 = No 1 = Yes)'], '0');
        $this->assertSame($this->row['Condition'], 'new');
        $this->assertSame($this->row['Customizable (0 = No 1 = Yes)'], '0');
        $this->assertSame($this->row['Uploadable files (0 = No 1 = Yes)'], '0');
        $this->assertSame($this->row['Text fields (0 = No 1 = Yes)'], '0');
        $this->assertSame($this->row['Action when out of stock'], '2');
        $this->assertSame($this->row['ID / Name of shop'], '1');
        $this->assertSame($this->row['Advanced Stock Management'], '0');
        $this->assertSame($this->row['Virtual product (0 = No 1 = Yes)'], '0');
        $this->assertSame($this->row['Number of allowed downloads'], '');
        $this->assertSame($this->row['Expiration date (yyyy-mm-dd)'], '');
        $this->assertSame($this->row['Number of days'], '');
//        $this->assertSame($this->row['shop'], '1'); todo change for multistore
        $this->assertSame($this->row['Delivery time of in-stock products'], '');
        $this->assertSame($this->row['Delivery time of out-of-stock products with allowed orders'], '');
        $this->assertSame($this->row['Low stock level'], '');
        $this->assertSame($this->row['Send me an email when the quantity is under this level'], '0');
        $this->assertSame($this->row['Categories (x y z...)'], 'Home,Clothes,Men');
//        $this->assertSame($this->row['Unit Price Ratio'], '0.000000');
//        $this->assertSame($this->row['Unity'], '');
        $this->assertSame($this->row['Supplier reference #'], '');
//        $this->assertSame($this->row['Manufacturer name'], 'Studio Design');
        $this->assertSame($this->row['Quantity'], '2400');
        $this->assertSame($this->row['Tags (x y z...)'], '');
        $this->assertSame($this->row['Image URLs (x y z...)'], 'http://prestashop-git/img/p/1/1.jpg');
        $this->assertSame($this->row['Image alt texts (x y z...)'], 'Hummingbird printed t-shirt,Hummingbird printed t-shirt');
//        $this->assertSame($this->row['delete_existing_images'], '0'); // it is always 0
        $this->assertSame($this->row['Feature (Name:Value:Position:Customized)'], 'Composition-Cotton,Property-Short sleeves');
        $this->assertSame($this->row['Depends on stock'], '');
        $this->assertSame($this->row['Warehouse'], '');
        $this->assertSame($this->row['File URL'], '');
        $this->assertSame($this->row['Accessories (x y z...)'], '');
    }

    /**
     * @param $x
     * @return bool
     */
    function check_your_datetime($x)
    {
        return (date('Y-m-d H:i:s', strtotime($x)) == $x);
    }
}
