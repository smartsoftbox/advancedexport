<?php

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
use Context;
use Advancedexport;
use AdvancedExportClass;
use Configuration;
use Product;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../advancedexport.php';
require_once dirname(__FILE__) . '/../../classes/AdvancedExportClass.php';

class ProductsImportTest extends IntegrationTestCase
{
    private static $dump;
    private $advancedExport;
    private $row;
    private $entity;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../config/config.inc.php';
        Context::getContext()->employee = new \Employee(1);
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
        $this->advancedExport = new Advancedexport();

        // create export models for import
        $advancedExportClass = $this->advancedExport->generateDefaultCsvByType($this->entity);
        // run export
        $this->advancedExport->createExportFile($advancedExportClass);
        // read files
        $url = _PS_ROOT_DIR_.'/modules/advancedexport/csv/' . $this->entity . '/' . $this->entity . '_import.csv';

        $rows = array_map('str_getcsv', file($url));
        foreach($rows[0] as $key => $fieldName) {
            $this->row[$fieldName] = $rows['1'][$key];
        }
    }

    public function test_CsvImportFileData()
    {
        $this->assertSame($this->row['Product Id'], '1');
        $this->assertSame($this->row['Active'], '1');
        $this->assertSame($this->row['Name'], 'Hummingbird printed t-shirt');
        $this->assertSame($this->row['Price Tax'], '22.944'); //todo in migration is 19.12
        $this->assertSame($this->row['Tax Id Rules Group'], '1');
        $this->assertSame($this->row['Wholesale Price'], '0.000000');
        $this->assertSame($this->row['On Sale'], '0');
        $this->assertSame($this->row['Discount amount'], ''); // reduction_price
        $this->assertSame($this->row['Discount percent'], '0.200000'); // reduction_percent
        $this->assertSame($this->row['Discount from (yyyy-mm-dd)'], '0000-00-00 00:00:00'); // reduction_from
        $this->assertSame($this->row['Discount to (yyyy-mm-dd)'], '0000-00-00 00:00:00'); // reduction_to
        $this->assertSame($this->row['Product Reference'], 'demo_1');
        $this->assertSame($this->row['Supplier Name (default)'], '');
        $this->assertSame($this->row['EAN 13'], '');
        $this->assertSame($this->row['Upc'], '');
        $this->assertSame($this->row['Ecotax'], '0.000000');
        $this->assertSame($this->row['Width'], '0.000000');
        $this->assertSame($this->row['Height'], '0.000000');
        $this->assertSame($this->row['Depth'], '0.000000');
        $this->assertSame($this->row['Weight'], '0.000000');
        $this->assertSame($this->row['Minimal Quantity'], '1');
        $this->assertSame($this->row['Visibility'], 'both');
        $this->assertSame($this->row['Additional Shipping Cost'], '0.00');
        $this->assertSame($this->row['Unity'], '');
        $this->assertSame($this->row['Unit Price Ratio'], '0.000000');
        $this->assertNotSame(false,strpos($this->row['Short Description'], 'Regular'));
        $this->assertNotSame(false,strpos($this->row['Long Description'], 'lightness'));
        $this->assertSame($this->row['Meta Title'], '');
        $this->assertSame($this->row['Meta Keywords'], '');
        $this->assertSame($this->row['Meta Description'], '');
        $this->assertSame($this->row['Link Rewrite'], 'hummingbird-printed-t-shirt');
        $this->assertSame($this->row['Available Now'], '');
        $this->assertSame($this->row['Available Later'], '');
        $this->assertSame($this->row['Available For Order'], '1');
        $this->assertSame($this->row['Product available date'], '0000-00-00');
        $this->assertSame($this->row['Date Added'], '2019-10-05 10:40:48');
        $this->assertSame($this->row['Show Price'], '1');
        $this->assertSame($this->row['Online only'], '0');
        $this->assertSame($this->row['Condition'], 'new');
        $this->assertSame($this->row['Customizable'], '0');
        $this->assertSame($this->row['Uploadable Files'], '0');
        $this->assertSame($this->row['Text Fields'], '0');
        $this->assertSame($this->row['Out Of Stock'], '2');
        $this->assertSame($this->row['Id shop default'], '1');
        $this->assertSame($this->row['Advanced stock management'], '0');
        $this->assertSame($this->row['Is Virtual'], '0');
        $this->assertSame($this->row['NB Downloadable'], '');
        $this->assertSame($this->row['Date Expiration'], '');
        $this->assertSame($this->row['Nb Days Accessible'], '');
//        $this->assertSame($this->row['shop'], '1'); todo change for multistore
        $this->assertSame($this->row['Delivery In Stock'], '');
        $this->assertSame($this->row['Delivery Out Stock'], '');
        $this->assertSame($this->row['Low Stock Threshold'], '');
        $this->assertSame($this->row['Low Stock Alert'], '0');
        $this->assertSame($this->row['Categories Names'], 'Home,Clothes,Men');
        $this->assertSame($this->row['Unit Price Ratio'], '0.000000');
        $this->assertSame($this->row['Unity'], '');
        $this->assertSame($this->row['Supplier Reference'], '');
        $this->assertSame($this->row['Manufacturer Name'], 'Studio Design');
        $this->assertSame($this->row['Quantity'], '2400');
        $this->assertSame($this->row['Tags'], '');
        $this->assertSame($this->row['Cover'], 'http://prestashop-git/img/p/1/1.jpg');
        $this->assertSame($this->row['Image alt'], 'Hummingbird printed t-shirt,Hummingbird printed t-shirt');
//        $this->assertSame($this->row['delete_existing_images'], '0'); // it is always 0
        $this->assertSame($this->row['Features'], 'Composition-Cotton,Property-Short sleeves');
        $this->assertSame($this->row['Depends On Stock'], '');
        $this->assertSame($this->row['Warehouse'], '');
        $this->assertSame($this->row['File URL'], '');
        $this->assertSame($this->row['Accessories'], '');
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
