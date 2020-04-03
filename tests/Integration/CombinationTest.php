<?php

namespace LegacyTests\TestCase\Integration\classes;

use Employee;
use LegacyTests\TestCase\IntegrationTestCase;
use PHPUnit_Framework_Assert as Assert;
use LegacyTests\TestCase\DatabaseDump;
use Exception;
use Address;
use Carrier;
use Cart;
use CartRule;
use Configuration;
use Context;
use Currency;
use Db;
use Group;
use Order;
use Product;
use Tools;
use Tax;
use TaxRulesGroup;
use TaxRule;
use AdvancedExport;
use AdvancedExportClass;

class CombinationTest extends IntegrationTestCase
{
    private static $dump;
    private $ae;
    private $row;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Save the database to restore it later: we're not the only test running so let's leave things
        // the way we found them.
        self::$dump = DatabaseDump::create();
        // Some tests might have cleared the configuration
        Configuration::loadConfiguration();

        Context::getContext()->employee = new Employee(1);
    }

    public static function tearDownAfterClass()
    {
        // After the test, we restore the database in the state it was
        // before we started.
        self::$dump->restore();
    }

    /**
     * Provide sensible defaults for tests that don't specify them.
     */
    public function setUp()
    {
        $this->ae = new AdvancedExport();

        $id = $this->createModelWithAllFieldsAndDefaultSettings('products');
        $aec = new AdvancedExportClass($id);
        $this->ae->createExportFile($aec);

        $url = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/products/test_products_combination.csv';
        $rows = array_map('str_getcsv', file($url));;
        foreach ($rows[0] as $key => $fieldname) {
            $this->row[$fieldname] = $rows['1'][$key];
        }
    }

    public function test_CsvFileData()
    {
        //array('name' => 'Product Id', 'field' => 'id_product', 'database' => 'products', 'import' => 1, 'import_combination' => 1, 'import_combination_name' => 'Product ID*', 'import_name' => 'ID', 'alias' => 'p'),
        $this->assertSame($this->row['Product Id'], '1');
        //array('name' => 'Product Reference', 'field' => 'reference', 'database' => 'products', 'import' => 13,  'import_combination' => 5, 'import_combination_name' => 'Reference', 'import_name' => 'Reference #', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Product Reference'], '');
        //array('name' => 'Name', 'field' => 'name', 'database' => 'products_lang', 'import' => 2, 'import_name' => 'Name *', 'alias' => 'pl'),
        $this->assertSame($this->row['Name'], 'Faded Short Sleeves T-shirt');
        //array('name' => 'Short Description', 'field' => 'description_short', 'database' => 'products_lang', 'import' => 30, 'import_name' => 'Description', 'alias' => 'pl'),
        $this->assertSame($this->row['Short Description'],
            '<p>Faded short sleeves t-shirt with high neckline. Soft and stretchy material for a comfortable fit. Accessorize with a straw hat and you\'re ready for summer!</p>');
        //array('name' => 'Long Description', 'field' => 'description', 'database' => 'products_lang', 'import' => 31, 'import_name' => 'Short description', 'alias' => 'pl'),
        $this->assertSame($this->row['Long Description'],
            '<p>Fashion has been creating well-designed collections since 2010. The brand offers feminine designs delivering stylish separates and statement dresses which have since evolved into a full ready-to-wear collection in which every item is a vital part of a woman\'s wardrobe. The result? Cool, easy, chic looks with youthful elegance and unmistakable signature style. All the beautiful pieces are made in Italy and manufactured with the greatest attention. Now Fashion extends to a range of accessories including shoes, hats, belts and more!</p>');
        //array('name' => 'Quantity', 'field' => 'quantity', 'database' => 'other', 'import' => 24, 'import_name' => 'Quantity', 'import_combination' => 10, 'import_combination_name' => 'Quantity', 'attribute' => true),
        $this->assertSame($this->row['Quantity'], '299');
        //array('name' => 'Price', 'field' => 'price', 'database' => 'products', 'alias' => 'p', 'import_combination' => 9, 'import_combination_name' => 'Impact on Price', 'attribute' => true),
        //should be 16.51000 but if id currency is 0 default
        //function automatically convert
        //todo-change that for proper value
        $this->assertSame($this->row['Price'], '16.51');
        //array('name' => 'Price Catalogue TTC', 'field' => 'price_tax_nodiscount', 'database' => 'other', 'attribute' => true),
        //should be 19.812 but if id currency is 0 default
        //function automatically convert
        //todo-change that for proper value
        $this->assertSame($this->row['Price Catalogue TTC'], '19.81');
        //array('name' => 'Price Tax', 'field' => 'price_tax', 'database' => 'other', 'import' => 5, 'import_name' => 'Price tax included',  'attribute' => true),
        $this->assertSame($this->row['Price Tax'], '19.812');
        //array('name' => 'Wholesale Price', 'field' => 'wholesale_price', 'database' => 'products', 'import' => 7,  'import_combination' => 8, 'import_name' => 'Wholesale price', 'alias' => 'p', 'attribute' => true),
        //impact
        $this->assertSame($this->row['Wholesale Price'], '0.000000');
        //array('name' => 'Supplier Id (default)', 'field' => 'id_supplier', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Supplier Id (default)'], '1');
        //array('name' => 'Suppliers Ids', 'field' => 'id_supplier_all', 'database' => 'other', 'attribute' => true),
        //specific supplier for
        //this attribute
        $this->assertSame($this->row['Suppliers Ids'], '');
        //array('name' => 'Supplier Name (default)', 'field' => 'supplier_name', 'as' => true, 'database' => 'supplier', 'import' => 15, 'import_name' => 'Supplier', 'alias' => 's'),
        $this->assertSame($this->row['Supplier Name (default)'], 'Fashion Supplier');
        //array('name' => 'Supplier Names', 'field' => 'supplier_name_all', 'database' => 'other', 'attribute' => true),
        $this->assertSame($this->row['Supplier Names'], '');
        //array('name' => 'Manufacturer Id', 'field' => 'id_manufacturer', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Manufacturer Id'], '1');
        //array('name' => 'Manufacturer Name', 'field' => 'manufacturer_name', 'database' => 'other', 'import' => 16, 'import_name' => 'Manufacturer'),
        $this->assertSame($this->row['Manufacturer Name'], 'Fashion Manufacturer');
        //array('name' => 'Tax Id Rules Group', 'field' => 'id_tax_rules_group', 'database' => 'products', 'import' => 6, 'import_name' => 'Tax rules ID', 'alias' => 'p'),
        $this->assertSame($this->row['Tax Id Rules Group'], '1');
        //array('name' => 'Tax Rate', 'field' => 'tax_rate', 'database' => 'other'),
        $this->assertSame($this->row['Tax Rate'], '20');
        //array('name' => 'Default Category Id', 'field' => 'id_category_default', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Default Category Id'], '5');
        //array('name' => 'Default Category Name', 'field' => 'nameCategoryDefault', 'database' => 'other'),
        $this->assertSame($this->row['Default Category Name'], 'T-shirts');
        //array('name' => 'Categories Names', 'field' => 'categories_names', 'database' => 'other'),
        $this->assertSame($this->row['Categories Names'], 'Home,Women,Tops,T-shirts');
        //array('name' => 'Categories Ids', 'field' => 'categories_ids', 'database' => 'other', 'import' => 4, 'import_name' => 'Categories (x,y,z...)'),
        $this->assertSame($this->row['Categories Ids'], '2,3,4,5');
        //array('name' => 'On Sale', 'field' => 'on_sale', 'database' => 'products', 'import' => 8, 'import_name' => 'On sale (0/1)', 'alias' => 'p'),
        $this->assertSame($this->row['On Sale'], '0');
        //array('name' => 'EAN 13', 'field' => 'ean13', 'database' => 'products', 'alias' => 'p', 'import' => 17,  'import_combination' => 6, 'import_combination_name' => 'EAN 13', 'import_name' => 'EAN13', 'attribute' => true),
        $this->assertSame($this->row['EAN 13'], '');
        //array('name' => 'Supplier Reference', 'field' => 'supplier_reference', 'database' => 'other', 'import' => 14,  'import_combination' => 4, 'import_combination_name' => 'Supplier reference', 'import_name' => 'Supplier reference #', 'attribute' => true),
        $this->assertSame($this->row['Supplier Reference'], '');
        //array('name' => 'Date Added', 'field' => 'date_add', 'database' => 'products', 'import' => 40, 'import_name' => 'Product creation date', 'alias' => 'p'),
        $this->assertTrue($this->check_your_datetime($this->row['Date Added']));
        //array('name' => 'Date Update', 'field' => 'date_upd', 'database' => 'products', 'alias' => 'p'),
        $this->assertTrue($this->check_your_datetime($this->row['Date Update']));
        //array('name' => 'Active', 'field' => 'active', 'database' => 'products', 'import' => 2, 'import_name' => 'Active (0/1)', 'alias' => 'p'),
        $this->assertSame($this->row['Active'], '1');
        //array('name' => 'Meta Title', 'field' => 'meta_title', 'database' => 'products_lang', 'import' => 33, 'import_name' => 'Meta title', 'alias' => 'pl'),
        $this->assertSame($this->row['Meta Title'], '');
        //array('name' => 'Meta Description', 'field' => 'meta_description', 'database' => 'products_lang', 'import' => 35, 'import_name' => 'Meta description', 'alias' => 'pl'),
        $this->assertSame($this->row['Meta Description'], '');
        //array('name' => 'Meta Keywords', 'field' => 'meta_keywords', 'database' => 'products_lang', 'import' => 35, 'import_name' => 'Meta keywords', 'alias' => 'pl'),
        $this->assertSame($this->row['Meta Keywords'], '');
        //array('name' => 'Available Now', 'field' => 'available_now', 'database' => 'products_lang', 'import' => 36, 'import_name' => 'Text when in stock', 'alias' => 'pl'),
        $this->assertSame($this->row['Available Now'], 'In stock');
        //array('name' => 'Available Later', 'field' => 'available_later', 'database' => 'products_lang', 'import' => 37, 'import_name' => 'Text when backorder allowed', 'alias' => 'pl'),
        $this->assertSame($this->row['Available Later'], '');
        //array('name' => 'Tags', 'field' => 'tags', 'database' => 'other', 'import' => 32, 'import_name' => 'Tags (x,y,z...)'),
        $this->assertSame($this->row['Tags'], '');
        //array('name' => 'Accessories', 'field' => 'accessories', 'database' => 'other'),
        $this->assertSame($this->row['Accessories'], '');
        //array('name' => 'Images', 'field' => 'images', 'database' => 'other', 'attribute' => true, 'import_combination' => 16, 'import_combination_name' => 'Image URLs (x,y,z...)'),
        $this->assertSame($this->row['Images'],
            'http://localhost:8888/presta16110t/1-home_default/faded-short-sleeves-tshirt.jpg,http://localhost:8888/presta1704t/2-home_default/faded-short-sleeves-tshirt.jpg');
        //array('name' => 'Online only', 'field' => 'online_only', 'database' => 'products', 'import' => 47, 'import_name' => 'Available online only (0 = No, 1 = Yes)', 'alias' => 'p'),
        $this->assertSame($this->row['Online only'], '0');
        //array('name' => 'Upc', 'field' => 'upc', 'database' => 'products', 'import' => 18,  'import_combination' => 7, 'import_combination_name' => 'UPC', 'import_name' => 'UPC', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Upc'], '');
        //array('name' => 'Ecotax', 'field' => 'ecotax', 'database' => 'products', 'import' => 19, 'import_combination' => 9, 'import_combination_name' => 'EcoTax', 'import_name' => 'Ecotax', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Ecotax'], '0.000000');
        //array('name' => 'Unity', 'field' => 'unity', 'database' => 'products', 'import' => 28, 'import_name' => 'Unity', 'alias' => 'p'),
        $this->assertSame($this->row['Unity'], '');
        //array('name' => 'Unit Price Ratio', 'field' => 'unit_price_ratio', 'database' => 'products', 'import' => 29, 'import_name' => 'Unit Price', 'alias' => 'p'),
        $this->assertSame($this->row['Unit Price Ratio'], '0.000000');
        //array('name' => 'Minimal Quantity', 'field' => 'minimal_quantity', 'database' => 'products', 'import' => 25, 'import_combination' => 11, 'import_combination_name' => 'Minimal quantity', 'import_name' => 'Minimal quantity', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Minimal Quantity'], '1');
        //array('name' => 'Additional Shipping Cost', 'field' => 'additional_shipping_cost', 'database' => 'products', 'import' => 27, 'import_name' => 'Additional shipping cost', 'alias' => 'p'),
        $this->assertSame($this->row['Additional Shipping Cost'], '0.00');
        //array('name' => 'Location', 'field' => 'location', 'database' => 'products', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Location'], '');
        //array('name' => 'Width', 'field' => 'width', 'database' => 'products', 'import' => 20, 'import_name' => 'Width', 'alias' => 'p'),
        $this->assertSame($this->row['Width'], '0.000000');
        //array('name' => 'Height', 'field' => 'height', 'database' => 'products', 'import' => 21, 'import_name' => 'Height', 'alias' => 'p'),
        $this->assertSame($this->row['Height'], '0.000000');
        //array('name' => 'Depth', 'field' => 'depth', 'database' => 'products', 'import' => 22, 'import_name' => 'Depth', 'alias' => 'p'),
        $this->assertSame($this->row['Depth'], '0.000000');
        //array('name' => 'Weight', 'field' => 'weight', 'database' => 'products', 'import' => 23, 'import_combination' => 10, 'import_combination_name' => 'Impact on weight', 'import_name' => 'Weight', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Weight'], '0.000000');
        //array('name' => 'Out Of Stock', 'field' => 'out_of_stock', 'database' => 'products', 'import' => 53, 'import_name' => 'Out of stock', 'alias' => 'p'),
        $this->assertSame($this->row['Out Of Stock'], '2');
        //array('name' => 'Quantity Discount', 'field' => 'quantity_discount', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Quantity Discount'], '0');
        //array('name' => 'Customizable', 'field' => 'customizable', 'database' => 'products', 'import' => 49, 'import_name' => 'Customizable (0 = No, 1 = Yes)', 'alias' => 'p'),
        $this->assertSame($this->row['Customizable'], '0');
        //array('name' => 'Uploadable Files', 'field' => 'uploadable_files', 'database' => 'products', 'import' => 50, 'import_name' => 'Uploadable files (0 = No, 1 = Yes)', 'alias' => 'p'),
        $this->assertSame($this->row['Uploadable Files'], '0');
        //array('name' => 'Text Fields', 'field' => 'text_fields', 'database' => 'products', 'import' => 52, 'import_name' => 'Text fields (0 = No, 1 = Yes)', 'alias' => 'p'),
        $this->assertSame($this->row['Text Fields'], '0');
        //array('name' => 'Available For Order', 'field' => 'available_for_order', 'database' => 'products', 'import' => 38, 'import_name' => 'Available for order (0 = No, 1 = Yes)', 'alias' => 'p'),
        $this->assertSame($this->row['Available For Order'], '1');
        //array('name' => 'Condition', 'field' => 'condition', 'database' => 'products', 'import' => 48, 'import_name' => 'Condition', 'alias' => 'p'),
        $this->assertSame($this->row['Condition'], 'new');
        //array('name' => 'Show Price', 'field' => 'show_price', 'database' => 'products', 'import' => 41, 'import_name' => 'Show Price', 'alias' => 'p'),
        $this->assertSame($this->row['Show Price'], '1');
        //array('name' => 'Indexed', 'field' => 'indexed', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Indexed'], '1');
        //array('name' => 'Cache Is Pack', 'field' => 'cache_is_pack', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Cache Is Pack'], '0');
        //array('name' => 'Cache Has Attachments', 'field' => 'cache_has_attachments', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Cache Has Attachments'], '0');
        //array('name' => 'Cache Default Attribute', 'field' => 'cache_default_attribute', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Cache Default Attribute'], '1');
        //array('name' => 'Link Rewrite', 'field' => 'link_rewrite', 'database' => 'products_lang', 'import' => 36, 'import_name' => 'URL rewritten', 'alias' => 'pl'),
        $this->assertSame($this->row['Link Rewrite'], 'faded-short-sleeves-tshirt');
        //array('name' => 'Url Product', 'field' => 'url_product', 'database' => 'other'),
        $this->assertSame($this->row['Url Product'],
            'http://localhost:8888/presta1704t/tshirts/1-faded-short-sleeves-tshirt.html');
        //array('name' => 'Features', 'field' => 'features', 'database' => 'other', 'import' => 46, 'import_name' => 'Feature(Name:Value:Position)'),
        $this->assertSame($this->row['Features'], 'Compositions-Cotton,Styles-Casual,Properties-Short Sleeve');
        //array('name' => 'Attributes', 'field' => 'attributes', 'database' => 'other', 'attribute' => true),
        //todo should be no space at the end
        $this->assertSame($this->row['Attributes'], 'Size: S Color: Orange ');
        //array('name' => 'Attributes Name', 'field' => 'attributes_name', 'database' => 'other', 'attribute' => true, 'import_combination' => 2, 'import_combination_name' => 'Attributes Name'),
        $this->assertSame($this->row['Attributes Name'], 'Size:select:0,Color:color:2');
        //array('name' => 'Attributes Value', 'field' => 'attributes_value', 'database' => 'other', 'attribute' => true, 'import_combination' => 3, 'import_combination_name' => 'Attributes Value'),
        $this->assertSame($this->row['Attributes Value'], 'S:0,Orange:8');
        //array('name' => 'Visibility', 'field' => 'visibility', 'database' => 'products', 'import' => 26, 'import_name' => 'Visibility', 'alias' => 'p'),
        $this->assertSame($this->row['Visibility'], 'both');
        //array('name' => 'Product available date', 'field' => 'available_date', 'database' => 'products', 'import' => 39, 'import_name' => 'Product available date', 'alias' => 'p'),
        $this->assertSame($this->row['Product available date'], '0000-00-00');
        //array('name' => 'Discount amount', 'field' => 'discount_amount', 'database' => 'specific_price', 'import' => 9, 'import_name' => 'Discount amount', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount amount'], '');
        //array('name' => 'Discount percent', 'field' => 'discount_percent', 'database' => 'specific_price', 'import' => 10, 'import_name' => 'Discount percent', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount percent'], '');
        //array('name' => 'Discount from (yyyy-mm-dd)', 'field' => 'from', 'database' => 'specific_price', 'import' => 11, 'import_name' => 'Discount from (yyyy-mm-dd)', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount from (yyyy-mm-dd)'], '');
        //array('name' => 'Discount to (yyyy-mm-dd)', 'field' => 'to', 'database' => 'specific_price', 'import' => 12, 'import_name' => 'Discount to (yyyy-mm-dd)', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount to (yyyy-mm-dd)'], "");
        //array('name' => 'Cover', 'field' => 'image', 'database' => 'other', 'import' => 42, 'import_name' => 'Image URLs (x,y,z...)'),
        $this->assertSame($this->row['Cover'],
            'http://localhost:8888/presta1704t/1-home_default/faded-short-sleeves-tshirt.jpg');
        //array('name' => 'Id shop default', 'field' => 'id_shop_default', 'database' => 'products', 'import' => 54, 'import_name' => 'ID / Name of shop', 'alias' => 'p', 'import_combination' => 2, 'import_combination_name' => 'ID / Name of shop'),
        $this->assertSame($this->row['Id shop default'], '1');
        //array('name' => 'Advanced stock management', 'field' => 'advanced_stock_management', 'database' => 'products', 'import' => 55, 'import_name' => 'Advanced stock managment', 'import_combination' => 20, 'import_combination_name' => 'Advanced stock managment', 'alias' => 'p'),
        $this->assertSame($this->row['Advanced stock management'], '0');
        //array('name' => 'Depends On Stock', 'field' => 'depends_on_stock', 'database' => 'other', 'import' => 56, 'import_name' => 'Depends On Stock', 'import_combination' => 21, 'import_combination_name' => 'Depends on stock'),
        $this->assertSame($this->row['Depends On Stock'], '');
        //array('name' => 'Warehouse', 'field' => 'warehouse', 'database' => 'other', 'import' => 57, 'import_name' => 'Warehouse', 'import_combination' => 22, 'import_combination_name' => 'Warehouse'),
        $this->assertSame($this->row['Warehouse'], '');
        //array('name' => 'Image alt', 'field' => 'image_alt', 'database' => 'other', 'import' => 17, 'import_name' => 'Image alt', 'import_combination' => 17, 'import_combination_name' => 'Image alt texts (x,y,z...)', 'attribute' => true),
        $this->assertSame($this->row['Image alt'], '');
        //array('name' => 'Image position', 'field' => 'image_position', 'database' => 'other', 'import' => 15, 'import_name' => 'Image position', 'import_combination' => 16, 'import_combination_name' => 'Image position', 'attribute' => true),
        $this->assertSame($this->row['Image position'], '1,2');
        //array('name' => 'Default (0 = No 1 = Yes)', 'field' => 'default_combination', 'database' => 'other', 'import_combination' => 16, 'import_combination_name' => 'Default (0 = No, 1 = Yes)', 'attribute' => true),
        $this->assertSame($this->row['Default (0 = No 1 = Yes)'], '1');
    }


    /**
     * @param $type
     * @return AdvancedExportClass
     */
    public function createModelWithAllFieldsAndDefaultSettings($type)
    {
        $aec = null;
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'advancedexport WHERE type = "' . $type . '"
                AND filename = "test_' . $type . '_combination"';

        $result = Db::getInstance()->ExecuteS($query);

        if (count($result) == 0) {
            $aec = new AdvancedExportClass();
            $aec->delimiter = ',';
            $aec->separator = '"';
            $aec->add_header = true;
            $aec->id_lang = Configuration::get('PS_LANG_DEFAULT');
            $aec->charset = 'UTF-8';
            $aec->decimal_round = -1;
            $aec->decimal_separator = -1;
            $aec->strip_tags = 0;
            $aec->only_new = 0;
            $aec->last_exported_id = 0;
            $aec->start_id = 0;
            $aec->end_id = 0;
            $aec->image_type = "home_default";
            $aec->type = $type;
            $aec->name = 'test';
            $aec->filename = 'test_' . $type . '_combination';
            $aec->fields = Tools::jsonEncode(
                [
                    'fields[]' => $this->getFieldsNames($type),
                    "active" => "0",
                    "out_of_stock" => "0",
                    "ean" => "0",
                    "attributes" => "1"
                ]
            );
            $aec->add();

            return $aec->id;
        } else {
            return $result[0]['id_advancedexport'];
        }
    }

    /**
     * @param $x
     * @return bool
     */
    function check_your_datetime($x)
    {
        return (date('Y-m-d H:i:s', strtotime($x)) == $x);
    }

    /**
     * @param $type
     * @return array
     */
    private function getFieldsNames($type)
    {
        $result = [];
        foreach ($this->ae->$type as $field) {
            $result[] = $field['field'];
        }
        return $result;
    }
}
