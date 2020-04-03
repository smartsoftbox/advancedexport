<?php

namespace LegacyTests\TestCase;

use Employee;
use PrestaShopBundle\Install\DatabaseDump;
use Context;
use Export;
use AdvancedExportClass;
use Configuration;
use Product;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../tests/Integration/HelperImport.php';

class CombinationImportTest extends IntegrationTestCase
{
    private static $dump;
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
        $this->row = HelperImport::getExportFileAsCsv(new Advancedexport(), 'combination');
    }

    public function test_CsvImportFileData()
    {
        // [{"id_product":"1","available_date":"0000-00-00","shop":"1","advanced_stock_management":"0",
        //"wholesale_price":"0.000000","price":"0.000000","weight":"0.000000","reference":"demo_1",
        //"supplier_reference":"","ean13":"","upc":"","minimal_quantity":"1","quantity":"300","id_image":"2",
        //"images":[{"id_image":"2"}],"default_on":"1","ecotax":"0.000000","id_product_attribute":"1",
        //"attributes_name":[["Size","1"],["Color","2"]],"attributes_value":[["S","1"],["White","8"]],
        //"group":"Size:select:0,Color:color:1","attribute":"S:0,White:3","image_alt":"Hummingbird printed t-shirt",
        //"image_position":"2","image_url":"http:\/\/prestashop-git\/img\/p\/2\/2.jpg","depends_on_stock":false,"warehouse":""},

        $this->assertSame($this->row['Product Id'], '1');
        $this->assertSame($this->row['Product Reference'], 'demo_1'); // todo check reference
        $this->assertSame($this->row['Attributes Name'], 'Size:select:0,Color:color:1');
        $this->assertSame($this->row['Attributes Value'], 'S:0,White:3');
        $this->assertSame($this->row['Supplier Reference'], '');
//        reference not need if id
//        $this->assertSame($this->row['Reference'], '1');
        $this->assertSame($this->row['EAN 13'], ''); // reduction_price
        $this->assertSame($this->row['Upc'], ''); // reduction_percent
        $this->assertSame($this->row['Wholesale Price'], '0.000000');
        $this->assertSame($this->row['Price'], '0.000000');
        $this->assertSame($this->row['Ecotax'], '0.000000');
        $this->assertSame($this->row['Quantity'], '300');
        $this->assertSame($this->row['Minimal Quantity'], '1');
        $this->assertSame($this->row['Low Stock Threshold'], '');
        $this->assertSame($this->row['Low Stock Alert'], '0');
        $this->assertSame($this->row['Weight'], '0.000000');
        $this->assertSame($this->row['Default (0 = No 1 = Yes)'], '1');
        $this->assertSame($this->row['Product available date'], '0000-00-00');
        $this->assertSame($this->row['Image position'], '2');
        $this->assertSame($this->row['Images'], 'http://prestashop-git/img/p/2/2.jpg');
        $this->assertSame($this->row['Image alt'], 'Hummingbird printed t-shirt');
//        $this->assertSame($this->row['Shop'], '0000-00-00'); todo check shops id
        $this->assertSame($this->row['Advanced stock management'], '0');
        $this->assertSame($this->row['Depends On Stock'], '');
        $this->assertSame($this->row['Warehouse'], '');
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
