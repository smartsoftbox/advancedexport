<?php

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
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
use PrestaShopBundle\Security\Admin\Employee;
use PrestaShopDatabaseException;
use Product;
use Tools;
use Tax;
use TaxRulesGroup;
use TaxRule;
use Export;
use Validate;
use Customer;
use AdvancedExportClass;

require_once dirname(__FILE__) . '/../../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../../classes/Field/CustomFields.php';

class Combination17Test extends IntegrationTestCase
{
    private static $dump;
    private $ae;
    private $row;
    private $row7;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../../config/config.inc.php';
        Context::getContext()->employee = new \Employee(1);
    }


    protected function tearDown()
    {
    }

    /**
     * Provide sensible defaults for tests that don't specify them.
     */
    public function setUp()
    {
        $this->ae = new Export();

        $id = $this->createModelWithAllFieldsAndDefaultSettings('products');
        $aec = new AdvancedExportClass($id);
        $this->ae->createExportFile($aec);

        $url = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/products/test_products_combination.csv';
        $rows = array_map('str_getcsv', file($url));;
        foreach ($rows[0] as $key => $fieldname) {
            $this->row[$fieldname] = $rows[1][$key];
        }

        foreach ($rows[0] as $key => $fieldname) {
            $this->row7[$fieldname] = $rows[22][$key];
        }
    }

    public function test_CsvFileData()
    {
        //array('name' => 'Product Id', 'field' => 'id_product', 'database' => 'products', 'import' => 1, 'import_combination' => 1, 'import_combination_name' => 'Product ID*', 'import_name' => 'ID', 'alias' => 'p'),
        $this->assertSame($this->row['Product Id'], '1');
        //array('name' => 'Product Reference', 'field' => 'reference', 'database' => 'products', 'import' => 13,  'import_combination' => 5, 'import_combination_name' => 'Reference', 'import_name' => 'Reference #', 'alias' => 'p', 'attribute' => true),
        $this->assertSame($this->row['Product Reference'], 'demo_1');
        //array('name' => 'Name', 'field' => 'name', 'database' => 'products_lang', 'import' => 2, 'import_name' => 'Name *', 'alias' => 'pl'),
        $this->assertSame($this->row['Name'], 'Hummingbird printed t-shirt');
        //array('name' => 'Short Description', 'field' => 'description_short', 'database' => 'products_lang', 'import' => 30, 'import_name' => 'Description', 'alias' => 'pl'),
        $this->assertSame($this->row['Short Description'],
            '<p><span style="font-size:10pt;font-style:normal;">Regular fit, round neckline, short sleeves. Made of extra long staple pima cotton. </span></p>');
        //array('name' => 'Long Description', 'field' => 'description', 'database' => 'products_lang', 'import' => 31, 'import_name' => 'Short description', 'alias' => 'pl'),
        $this->assertSame($this->row['Long Description'],
            '<p><span style="font-size:10pt;font-style:normal;"><span style="font-size:10pt;font-style:normal;">Symbol of lightness and delicacy, the hummingbird evokes curiosity and joy.</span><span style="font-size:10pt;font-style:normal;"> Studio Design\' PolyFaune collection features classic products with colorful patterns, inspired by the traditional japanese origamis. To wear with a chino or jeans. The sublimation textile printing process provides an exceptional color rendering and a color, guaranteed overtime.</span></span></p>');
        //array('name' => 'Quantity', 'field' => 'quantity', 'database' => 'other', 'import' => 24, 'import_name' => 'Quantity', 'import_combination' => 10, 'import_combination_name' => 'Quantity', 'attribute' => true),
        $this->assertSame($this->row['Quantity'], '2400');
        //array('name' => 'Price', 'field' => 'price', 'database' => 'products', 'alias' => 'p', 'import_combination' => 9, 'import_combination_name' => 'Impact on Price', 'attribute' => true),
        //should be 16.51000 but if id currency is 0 default
        //function automatically convert
        //todo-change that for proper value
        $this->assertSame($this->row['Price'], '23.9');
        //array('name' => 'Price Catalogue TTC', 'field' => 'price_tax_nodiscount', 'database' => 'other', 'attribute' => true),
        //should be 19.812 but if id currency is 0 default
        //function automatically convert
        //todo-change that for proper value
        $this->assertSame($this->row['Price Catalogue TTC'], '28.68');
        //array('name' => 'Price Tax', 'field' => 'price_tax', 'database' => 'other', 'import' => 5, 'import_name' => 'Price tax included',  'attribute' => true),
        $this->assertSame($this->row['Price Tax'], '22.944');
        //array('name' => 'Wholesale Price', 'field' => 'wholesale_price', 'database' => 'products', 'import' => 7,  'import_combination' => 8, 'import_name' => 'Wholesale price', 'alias' => 'p', 'attribute' => true),
        //impact
        $this->assertSame($this->row['Wholesale Price'], '0.000000');
        //array('name' => 'Supplier Id (default)', 'field' => 'id_supplier', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Supplier Id (default)'], '0');
        //array('name' => 'Suppliers Ids', 'field' => 'id_supplier_all', 'database' => 'other', 'attribute' => true),
        //specific supplier for
        //this attribute
        $this->assertSame($this->row['Suppliers Ids'], '');
        //array('name' => 'Supplier Name (default)', 'field' => 'supplier_name', 'as' => true, 'database' => 'supplier', 'import' => 15, 'import_name' => 'Supplier', 'alias' => 's'),
        $this->assertSame($this->row['Supplier Name (default)'], '');
        //array('name' => 'Supplier Names', 'field' => 'supplier_name_all', 'database' => 'other', 'attribute' => true),
        $this->assertSame($this->row['Supplier Names'], '');
        //array('name' => 'Manufacturer Id', 'field' => 'id_manufacturer', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Manufacturer Id'], '1');
        //array('name' => 'Manufacturer Name', 'field' => 'manufacturer_name', 'database' => 'other', 'import' => 16, 'import_name' => 'Manufacturer'),
        $this->assertSame($this->row['Manufacturer Name'], 'Studio Design');
        //array('name' => 'Tax Id Rules Group', 'field' => 'id_tax_rules_group', 'database' => 'products', 'import' => 6, 'import_name' => 'Tax rules ID', 'alias' => 'p'),
        $this->assertSame($this->row['Tax Id Rules Group'], '1');
        //array('name' => 'Tax Rate', 'field' => 'tax_rate', 'database' => 'other'),
        $this->assertSame($this->row['Tax Rate'], '20');
        //array('name' => 'Default Category Id', 'field' => 'id_category_default', 'database' => 'products', 'alias' => 'p'),
        $this->assertSame($this->row['Default Category Id'], '4');
        //array('name' => 'Default Category Name', 'field' => 'nameCategoryDefault', 'database' => 'other'),
        $this->assertSame($this->row['Default Category Name'], 'Men');
        //array('name' => 'Categories Names', 'field' => 'categories_names', 'database' => 'other'),
        $this->assertSame($this->row['Categories Names'], 'Home,Clothes,Men');
        //array('name' => 'Categories Ids', 'field' => 'categories_ids', 'database' => 'other', 'import' => 4, 'import_name' => 'Categories (x,y,z...)'),
        $this->assertSame($this->row['Categories Ids'], '2,3,4');
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
        $this->assertSame($this->row['Available Now'], '');
        //array('name' => 'Available Later', 'field' => 'available_later', 'database' => 'products_lang', 'import' => 37, 'import_name' => 'Text when backorder allowed', 'alias' => 'pl'),
        $this->assertSame($this->row['Available Later'], '');
        //array('name' => 'Tags', 'field' => 'tags', 'database' => 'other', 'import' => 32, 'import_name' => 'Tags (x,y,z...)'),
        $this->assertSame($this->row['Tags'], '');
        //array('name' => 'Accessories', 'field' => 'accessories', 'database' => 'other'),
        $this->assertSame($this->row['Accessories'], '');
        //array('name' => 'Images', 'field' => 'images', 'database' => 'other', 'attribute' => true, 'import_combination' => 16, 'import_combination_name' => 'Image URLs (x,y,z...)'),
        $this->assertSame($this->row['Images'],
            'http://prestashop-git/img/p/1/1-home_default.jpg,http://prestashop-git/img/p/2/2-home_default.jpg');
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
        $this->assertSame($this->row['Link Rewrite'], 'hummingbird-printed-t-shirt');
        //array('name' => 'Url Product', 'field' => 'url_product', 'database' => 'other'),
        $this->assertSame($this->row['Url Product'],
            'http://prestashop-git/index.php?id_product=1&rewrite=hummingbird-printed-t-shirt&controller=product&id_lang=1');
        //array('name' => 'Features', 'field' => 'features', 'database' => 'other', 'import' => 46, 'import_name' => 'Feature(Name:Value:Position)'),
        $this->assertSame($this->row['Features'], 'Composition-Cotton,Property-Short sleeves');
        //array('name' => 'Visibility', 'field' => 'visibility', 'database' => 'products', 'import' => 26, 'import_name' => 'Visibility', 'alias' => 'p'),
        $this->assertSame($this->row['Visibility'], 'both');
        //array('name' => 'Product available date', 'field' => 'available_date', 'database' => 'products', 'import' => 39, 'import_name' => 'Product available date', 'alias' => 'p'),
        $this->assertSame($this->row['Product available date'], '0000-00-00');
        //array('name' => 'Discount amount', 'field' => 'discount_amount', 'database' => 'specific_price', 'import' => 9, 'import_name' => 'Discount amount', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount amount'], '');
        //array('name' => 'Discount percent', 'field' => 'discount_percent', 'database' => 'specific_price', 'import' => 10, 'import_name' => 'Discount percent', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount percent'], '0.200000');
        //array('name' => 'Discount from (yyyy-mm-dd)', 'field' => 'from', 'database' => 'specific_price', 'import' => 11, 'import_name' => 'Discount from (yyyy-mm-dd)', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount from (yyyy-mm-dd)'], '0000-00-00 00:00:00');
        //array('name' => 'Discount to (yyyy-mm-dd)', 'field' => 'to', 'database' => 'specific_price', 'import' => 12, 'import_name' => 'Discount to (yyyy-mm-dd)', 'alias' => 'sp_tmp'),
        $this->assertSame($this->row['Discount to (yyyy-mm-dd)'], "0000-00-00 00:00:00");
        //array('name' => 'Cover', 'field' => 'image', 'database' => 'other', 'import' => 42, 'import_name' => 'Image URLs (x,y,z...)'),
        $this->assertSame($this->row['Cover'], 'http://prestashop-git/img/p/1/1-home_default.jpg');
        //array('name' => 'Id shop default', 'field' => 'id_shop_default', 'database' => 'products', 'import' => 54, 'import_name' => 'ID / Name of shop', 'alias' => 'p', 'import_combination' => 2, 'import_combination_name' => 'ID / Name of shop'),
        $this->assertSame($this->row['Id shop default'], '1');
        //array('name' => 'Advanced stock management', 'field' => 'advanced_stock_management', 'database' => 'products', 'import' => 55, 'import_name' => 'Advanced stock managment', 'import_combination' => 20, 'import_combination_name' => 'Advanced stock managment', 'alias' => 'p'),
        $this->assertSame($this->row['Advanced stock management'], '0');
        //array('name' => 'Depends On Stock', 'field' => 'depends_on_stock', 'database' => 'other', 'import' => 56, 'import_name' => 'Depends On Stock', 'import_combination' => 21, 'import_combination_name' => 'Depends on stock'),
        $this->assertSame($this->row['Depends On Stock'], '');
        //array('name' => 'Warehouse', 'field' => 'warehouse', 'database' => 'other', 'import' => 57, 'import_name' => 'Warehouse', 'import_combination' => 22, 'import_combination_name' => 'Warehouse'),
        $this->assertSame($this->row['Warehouse'], '');
        //array('name' => 'Image alt', 'field' => 'image_alt', 'database' => 'other', 'import' => 17, 'import_name' => 'Image alt', 'import_combination' => 17, 'import_combination_name' => 'Image alt texts (x,y,z...)', 'attribute' => true),
        $this->assertSame($this->row['Image alt'], 'Hummingbird printed t-shirt,Hummingbird printed t-shirt');
        //array('name' => 'Image position', 'field' => 'image_position', 'database' => 'other', 'import' => 15, 'import_name' => 'Image position', 'import_combination' => 16, 'import_combination_name' => 'Image position', 'attribute' => true),
        $this->assertSame($this->row['Image position'], '1,2');
        //array('name' => 'Default (0 = No 1 = Yes)', 'field' => 'default_combination', 'database' => 'other', 'import_combination' => 16, 'import_combination_name' => 'Default (0 = No, 1 = Yes)', 'attribute' => true),
        $this->assertSame($this->row['Product attachments url'], '');
        $this->assertSame($this->row['Is Virtual'], '0');
        $this->assertSame($this->row['NB Downloadable'], '');
        $this->assertsame($this->row['Date Expiration'], '');
        $this->assertSame($this->row['Nb Days Accessible'], '');
        $this->assertSame($this->row['File URL'], '');
        $this->assertSame($this->row['Delivery In Stock'], '');
        $this->assertSame($this->row['Delivery Out Stock'], '');
        $this->assertSame($this->row['Low Stock Threshold'], '');
        $this->assertSame($this->row['Low Stock Alert'], '0');
        $this->assertSame($this->row['Categories Path'], 'Clothes,Clothes > Men');

        // combination fields
        $this->assertSame($this->row['Combination Attributes'], 'Size: S;Color: White');
        $this->assertSame($this->row['Combination Attributes Name'], 'Size:select:0,Color:color:1');
        $this->assertSame($this->row['Combination Default (0 = No 1 = Yes)'], '1');
        $this->assertSame($this->row['Combination Reference'], 'demo_1');
        $this->assertSame($this->row['Combination Quantity'], '300');
        $this->assertSame($this->row['Combination Impact on Price'], '0.000000');
        $this->assertSame($this->row['Combination Price Catalogue TTC'], '28.68');
        $this->assertSame($this->row['Combination Price Tax'], '22.944');
        $this->assertSame($this->row['Combination Wholesale Price'], '0.000000');
        $this->assertSame($this->row['Combination MPN'], '');
        $this->assertSame($this->row['Combination Suppliers Ids'], '');
        $this->assertSame($this->row['Combination Supplier Names'], '');
        $this->assertSame($this->row['Combination EAN 13'], '');
        $this->assertSame($this->row['Combination Supplier Reference'], '');
        $this->assertSame($this->row['Combination Images'], 'http://prestashop-git/img/p/2/2-home_default.jpg');
        $this->assertSame($this->row['Combination Upc'], '');
        $this->assertSame($this->row['Combination Ecotax'], '0.000000');
        $this->assertSame($this->row['Combination Minimal Quantity'], '1');
        $this->assertSame($this->row['Combination Location'], '');
        $this->assertSame($this->row['Combination Weight'], '0.000000');
        $this->assertSame($this->row['Combination available date'], '0000-00-00');
        $this->assertSame($this->row['Combination Image alt'], 'Hummingbird printed t-shirt');
        $this->assertSame($this->row['Combination Image position'], '2');
        $this->assertSame($this->row['Combination Warehouse'], '');
        $this->assertSame($this->row['Combination Low Stock Threshold'], '');
        $this->assertSame($this->row['Combination Low Stock Alert'], '0');

        // product with no combination
        $this->assertSame($this->row7['Combination Attributes'], '');
        $this->assertSame($this->row7['Combination Attributes Name'], '');
        $this->assertSame($this->row7['Combination Default (0 = No 1 = Yes)'], '');
        $this->assertSame($this->row7['Combination Reference'], '');
        $this->assertSame($this->row7['Combination Quantity'], '');
        $this->assertSame($this->row7['Combination Impact on Price'], '');
        $this->assertSame($this->row7['Combination Price Catalogue TTC'], '');
        $this->assertSame($this->row7['Combination Price Tax'], '');
        $this->assertSame($this->row7['Combination Wholesale Price'], '');
        $this->assertSame($this->row7['Combination MPN'], '');
        $this->assertSame($this->row7['Combination Suppliers Ids'], '');
        $this->assertSame($this->row7['Combination Supplier Names'], '');
        $this->assertSame($this->row7['Combination EAN 13'], '');
        $this->assertSame($this->row7['Combination Supplier Reference'], '');
        $this->assertSame($this->row7['Combination Images'], '');
        $this->assertSame($this->row7['Combination Upc'], '');
        $this->assertSame($this->row7['Combination Ecotax'], '');
        $this->assertSame($this->row7['Combination Minimal Quantity'], '');
        $this->assertSame($this->row7['Combination Location'], '');
        $this->assertSame($this->row7['Combination Weight'], '');
        $this->assertSame($this->row7['Combination available date'], '');
        $this->assertSame($this->row7['Combination Image alt'], '');
        $this->assertSame($this->row7['Combination Image position'], '');
        $this->assertSame($this->row7['Combination Warehouse'], '');
        $this->assertSame($this->row7['Combination Low Stock Threshold'], '');
        $this->assertSame($this->row7['Combination Low Stock Alert'], '');
    }

    public function test_AllFieldsExported()
    {
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'advancedexportfield WHERE tab = "products"';
        $result = Db::getInstance()->ExecuteS($query);


        foreach ($result as $key => $value) {
            $this->assertSame(true, isset($this->row[$value['name']]));
        }
    }

    /**
     * @param $type
     * @return AdvancedExportClass
     */
    public function createModelWithAllFieldsAndDefaultSettings($type)
    {
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
        $aec->file_format = 'csv';
        $aec->fields = json_encode(
            [
                'fields[]' => $this->getFieldsNames($type),
                "active" => "0",
                "out_of_stock" => "0",
                "ean" => "0"
            ]
        );
        $aec->add();

        return $aec->id;
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
     * @throws PrestaShopDatabaseException
     */
    private function getFieldsNames($type)
    {
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'advancedexportfield WHERE tab = "' . $type . '"';
        $result = Db::getInstance()->ExecuteS($query);

        $return = [];
        foreach ($result as $field) {
            $return[$field['field']] = array($field['name']);
        }
        return $return;
    }
}
