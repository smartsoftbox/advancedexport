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
use Product;
use Tools;
use Tax;
use TaxRulesGroup;
use TaxRule;
use AdvancedExport;
use Validate;
use Customer;
use AdvancedExportClass;

require_once dirname(__FILE__) . '/../../advancedexport.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';

class Orders17Test extends IntegrationTestCase
{
    private static $dump;
    private $ae;
    private $row;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../config/config.inc.php';
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
        $this->ae = new AdvancedExport();

        $id = $this->createModelWithAllFieldsAndDefaultSettings('orders');
        $aec = new AdvancedExportClass($id);
        $this->ae->createExportFile($aec);

        $url = _PS_ROOT_DIR_.'/modules/advancedexport/csv/orders/test_orders.csv';
        $rows = array_map('str_getcsv', file($url));;
        foreach($rows[0] as $key => $fieldname) {
            $this->row[$fieldname] = $rows['1'][$key];
        }
    }

    public function test_CsvFileData()
    {
        //array('name' => 'Order No', 'field' => 'id_order', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Order No'], '1');
        //array('name' => 'Reference', 'field' => 'reference', 'database' => 'orders', 'alias' => 'o', 'attribute' => true),
        $this->assertSame($this->row['Reference'], 'XKBKNABJK');
        //array('name' => 'Code (voucher)', 'field' => 'code', 'database' => 'other'),
        $this->assertSame($this->row['Code (voucher)'], '');
        ////SHOP
        //array('name' => 'Payment module', 'field' => 'module', 'database' => 'orders',  'alias' => 'o'),
        $this->assertSame($this->row['Payment module'], 'ps_checkpayment');
        //array('name' => 'Payment', 'field' => 'payment', 'database' => 'orders',  'alias' => 'o'),
        $this->assertSame($this->row['Payment'], 'Payment by check');
        //array('name' => 'Total paid', 'field' => 'total_paid', 'database' => 'orders',  'alias' => 'o'),
        $this->assertSame($this->row['Total paid'], '61.800000');
        //array('name' => 'Total paid tax incl', 'field' => 'total_paid_tax_incl', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total paid tax incl'], '61.800000');
        //array('name' => 'Total paid tax excl', 'field' => 'total_paid_tax_excl', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total paid tax excl'], '61.800000');
        //array('name' => 'Total products with tax', 'field' => 'total_products_wt', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total products with tax'], '59.800000');
        //array('name' => 'Total paid real', 'field' => 'total_paid_real', 'database' => 'orders', 'alias' => 'o'),
        //todo check why 0 not 55
        $this->assertSame($this->row['Total paid real'], '0.000000');
        //array('name' => 'Total products', 'field' => 'total_products', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total products'], '59.800000');
        //array('name' => 'Total shipping', 'field' => 'total_shipping', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total shipping'], '2.000000');
        //array('name' => 'Total wrapping', 'field' => 'total_wrapping', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total wrapping'], '0.000000');
        //array('name' => 'Shipping number', 'field' => 'shipping_number', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Shipping number'], '');
        //array('name' => 'Delivery number', 'field' => 'delivery_number', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Delivery number'], '0');
        //array('name' => 'Invoice number', 'field' => 'invoice_number', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Invoice number'], '0');
        //array('name' => 'Invoice date', 'field' => 'invoice_date', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Invoice date'], '0000-00-00 00:00:00');
        //array('name' => 'Delivery date', 'field' => 'delivery_date', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Delivery date'], '0000-00-00 00:00:00');
        //array('name' => 'Date added', 'field' => 'date_add', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Date added'], '2019-10-05 10:40:50');
        //array('name' => 'Date updated', 'field' => 'date_upd', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Date updated'], '2019-10-05 10:40:50');
        //array('name' => 'Total discounts', 'field' => 'total_discounts', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Total discounts'], '0.000000');
        //array('name' => 'Gift message', 'field' => 'gift_message', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Gift message'], '');
        //array('name' => 'Valid', 'field' => 'valid', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Valid'], '0');
        //array('name' => 'Carrier id', 'field' => 'id_carrier', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Carrier id'], '2');
        //array('name' => 'Customer id', 'field' => 'id_customer', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Customer id'], '2');
        //array('name' => 'Recycled packaging', 'field' => 'recyclable', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Recycled packaging'], '0');
        //array('name' => 'Gift wrapping', 'field' => 'gift', 'database' => 'orders', 'alias' => 'o'),
        $this->assertSame($this->row['Gift wrapping'], '0');
        //array('name' => 'Customization', 'field' => 'customization', 'database' => 'other', 'alias' => 'o'),
        $this->assertSame($this->row['Customization'], '');
        ////PS_CUSTOMER
        //array('name' => 'Customer Firstname', 'field' => 'firstname', 'database' => 'customer', 'alias' => 'cu'),
        $this->assertSame($this->row['Customer Firstname'], 'John');
        //array('name' => 'Customer Lastname', 'field' => 'lastname', 'database' => 'customer', 'alias' => 'cu'),
        $this->assertSame($this->row['Customer Lastname'], 'DOE');
        //array('name' => 'Customer Email', 'field' => 'email', 'database' => 'customer', 'alias' => 'cu'),
        $this->assertSame($this->row['Customer Email'], 'pub@prestashop.com');
        //array('name' => 'Customer id language', 'field' => 'id_lang', 'database' => 'customer', 'alias' => 'cu'),
        $this->assertSame($this->row['Customer id language'], '1');
        ////PS_ADRESS
        //array('name' => 'Delivery Gender', 'field' => 'delivery_name', 'as' => true, 'database' => 'gender', 'alias' => 'gl'),
        $this->assertSame($this->row['Delivery Gender'], 'Mr.');
        //array('name' => 'Delivery Company Name', 'field' => 'company', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery Company Name'], 'My Company');
        //array('name' => 'Delivery Firstname', 'field' => 'delivery_firstname', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery Firstname'], 'John');
        //array('name' => 'Delivery Lastname', 'field' => 'delivery_lastname', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery Lastname'], 'DOE');
        //array('name' => 'Delivery address line 1', 'field' => 'delivery_address1', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery address line 1'], '16, Main street');
        //array('name' => 'Delivery address line 2', 'field' => 'delivery_address2', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery address line 2'], '2nd floor');
        //array('name' => 'Delivery postcode', 'field' => 'delivery_postcode', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery postcode'], '33133');
        //array('name' => 'Delivery city', 'field' => 'delivery_city', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery city'], 'Miami');
        //array('name' => 'Delivery phone', 'field' => 'delivery_phone', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery phone'], '0102030405');
        //array('name' => 'Delivery phone(mobile)', 'field' => 'delivery_phone_mobile', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery phone(mobile)'], '');
        //array('name' => 'Delivery VAT', 'field' => 'delivery_vat_number', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery VAT'], '');
        //array('name' => 'Delivery DNI', 'field' => 'delivery_dni', 'as' => true, 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['Delivery DNI'], '');
        ////PS_STATE
        //array('name' => 'Delivery country iso code', 'field' => 'iso_code', 'database' => 'country', 'alias' => 'co'),
        $this->assertSame($this->row['Delivery country iso code'], 'US');
        //array('name' => 'Delivery state', 'field' => 'state_name', 'as' => true, 'database' => 'state', 'alias' => 's'),
        $this->assertSame($this->row['Delivery state'], 'Florida');
        ////PS_COUNTRY_LANG
        //array('name' => 'Delivery country', 'field' => 'country_name', 'as' => true, 'database' => 'country_lang', 'alias' => 'cl'),
        $this->assertSame($this->row['Delivery country'], 'United States');
        ////PS_ADRESS
        //array('name' => 'Invoice address line 1', 'field' => 'invoice_address1', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice address line 1'], '16, Main street');
        //array('name' => 'Invoice address line 2', 'field' => 'invoice_address2', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice address line 2'], '2nd floor');
        //array('name' => 'Invoice postcode', 'field' => 'invoice_postcode', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice postcode'], '33133');
        //array('name' => 'Invoice city', 'field' => 'invoice_city', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice city'], 'Miami');
        //array('name' => 'Invoice phone', 'field' => 'invoice_phone', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice phone'], '0102030405');
        //array('name' => 'Invoice phone (mobile)', 'field' => 'invoice_phone_mobile', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice phone (mobile)'], '');
        //array('name' => 'Invoice gender', 'field' => 'invoice_name', 'as' => true, 'database' => 'gender', 'alias' => 'inv_gl'),
        $this->assertSame($this->row['Invoice gender'], 'Mr.');
        //array('name' => 'Invoice firstname', 'field' => 'invoice_firstname', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice firstname'], 'John');
        //array('name' => 'Invoice lastname', 'field' => 'invoice_lastname', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice lastname'], 'DOE');
        //array('name' => 'Invoice company name', 'field' => 'invoice_company', 'as' => true, 'database' => 'address', 'alias' => 'inv_a'),
        $this->assertSame($this->row['Invoice company name'], 'My Company');
        ////ORDER_PAYMENT
        //array('name' => 'Transaction Id', 'field' => 'transaction_id', 'database' => 'order_payment', 'alias' => 'op'),
        $this->assertSame($this->row['Transaction Id'], '');
        ////PS_CARRIER
        //array('name' => 'Name carrier', 'field' => 'carrier_name', 'as' => true, 'database' => 'carrier', 'alias' => 'ca'),
        $this->assertSame($this->row['Name carrier'], 'My carrier');
        ////PS_ORDER_DETAIL
        //array('name' => 'Product ID', 'field' => 'product_id', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product ID'], '1');
        //array('name' => 'Product Ref', 'field' => 'product_reference', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Ref'], 'demo_2');
        //array('name' => 'Product Name', 'field' => 'product_name', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Name'], 'Hummingbird printed t-shirt - Color : White, Size : S');
        //array('name' => 'Product Price', 'field' => 'product_price', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Price'], '23.900000');
        //array('name' => 'Product Quantity', 'field' => 'product_quantity', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Quantity'], '1');
        //array('name' => 'Shop name', 'field' => 'shop_name', 'database' => 'shop', 'as' => true, 'alias' => 'sh'),
        $this->assertSame($this->row['Shop name'], 'PrestaShop');
        //
        //array('name' => 'Message', 'field' => 'message', 'database' => 'message', 'alias' => 'm'),
        $this->assertSame($this->row['Message'], '');
        //array('name' => 'Order currency', 'field' => 'currency_iso_code', 'database' => 'currency', 'as' => true, 'alias' => 'cur'),
        $this->assertSame($this->row['Order currency'], 'EUR');
        //array('name' => 'Product quantity discount', 'field' => 'product_quantity_discount', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product quantity discount'], '0.000000');
        //array('name' => 'Product Reduction amount', 'field' => 'reduction_amount', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Reduction amount'], '0.000000');
        //array('name' => 'Product Reduction amount tax incl', 'field' => 'reduction_amount_tax_incl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Reduction amount tax incl'], '0.000000');
        //array('name' => 'Product Reduction amount tax excl', 'field' => 'reduction_amount_tax_excl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Reduction amount tax excl'], '0.000000');
        //array('name' => 'Product group reduction', 'field' => 'group_reduction', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product group reduction'], '0.00');
        //array('name' => 'Product ean13', 'field' => 'product_ean13', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product ean13'], '');
        //array('name' => 'Product Unit price tax incl', 'field' => 'unit_price_tax_incl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Unit price tax incl'], '23.900000');
        //array('name' => 'Product Unit price tax excl', 'field' => 'unit_price_tax_excl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Unit price tax excl'], '23.900000');
        //array('name' => 'Product Total price tax excl', 'field' => 'total_price_tax_incl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Total price tax incl'], '27.000000');
        //array('name' => 'Product Total price tax excl', 'field' => 'total_price_tax_excl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Total price tax excl'], '23.900000');
        //array('name' => 'Product Total shipping price tax excl', 'field' => 'total_shipping_price_tax_incl', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product Total shipping price tax excl'], '0.000000');
        //array('name' => 'Product ecotax', 'field' => 'ecotax', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product ecotax'], '0.000000');
        //array('name' => 'Product ecotax rate', 'field' => 'ecotax_tax_rate', 'database' => 'order_detail', 'alias' => 'od'),
        $this->assertSame($this->row['Product ecotax rate'], '0.000');
        //array('name' => 'Product tax rate (order detail table)', 'field' => 'tax_rate', 'database' => 'tax', 'alias' => 'od'),
        $this->assertSame($this->row['Product tax rate (order detail table)'], '0.000');
        //array('name' => 'Product tax rate (tax table)', 'field' => 'rate', 'database' => 'tax', 'alias' => 't'),
        $this->assertSame($this->row['Product tax rate (tax table)'], '');
        //array('name' => 'Product tax rate (tax table)', 'field' => 'unit_amount', 'database' => 'order_detail_tax', 'alias' => 'odt'),
        $this->assertSame($this->row['Product tax rate (tax table)'], '');
        //array('name' => 'Product tax total amount', 'field' => 'total_amount', 'database' => 'order_detail_tax', 'alias' => 'odt'),
        $this->assertSame($this->row['Product tax total amount'], '');
        //array('name' => 'Order state', 'field' => 'orderstate_name', 'database' => 'order_state_lang', 'as' => true, 'alias' => 'osl'),
        $this->assertSame($this->row['Order state'], 'Canceled');
        //array('name' => 'Employee name (last state)', 'field' => 'employee_name', 'database' => 'other'),
        $this->assertSame($this->row['Employee name (last state)'], 'John Doe');
        //array('name' => 'Delivery state iso', 'field' => 'state_iso_code', 'as' => true, 'database' => 'state', 'alias' => 's'),
        $this->assertSame($this->row['Delivery state iso'], 'FL');
    }

    public function test_AllFieldsExported()
    {
        $query = 'SELECT * FROM '._DB_PREFIX_.'advancedexportfield WHERE tab = "orders"';
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
        $aec->filename = 'test_' . $type;
        $aec->fields = Tools::jsonEncode(
            [
                'fields[]' => $this->getFieldsNames($type)
            ]
        );
        $aec->add();

        return $aec->id;
    }

    /**
     * @param $x
     * @return bool
     */
    function check_your_datetime($x) {
        return (date('Y-m-d H:i:s', strtotime($x)) == $x);
    }

    /**
     * @param $type
     * @return array
     * @throws \PrestaShopDatabaseException
     */
    private function getFieldsNames($type)
    {
        $query = 'SELECT * FROM '._DB_PREFIX_.'advancedexportfield WHERE tab = "'.$type.'"';
        $result = Db::getInstance()->ExecuteS($query);

        $return = [];
        foreach($result as $field) {
            $return[$field['field']] = array($field['name']);
        }
        return $return;
    }
}
