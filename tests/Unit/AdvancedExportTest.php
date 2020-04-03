<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../../advancedexport.php';

class AdvancedExportTest extends TestCase
{
    const ADVANCED_EXPORT = 'Advancedexport';
    public $ae;

    public function setup()
    {
        $this->ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMockShort($methods)
    {
        return $this->getMock(self::ADVANCED_EXPORT, $methods, [], '', false);
    }

//    public function test_Import_Product_Fields()
//    {
//        //ID
//        $this->assertSame(1, $this->getImportValueByFieldName('id_product'));
//        $this->assertSame('ID', $this->getImportNameByFieldName('id_product'));
//        //Active (0/1)
//        $this->assertSame(2, $this->getImportValueByFieldName('active'));
//        $this->assertSame('Active (0/1)', $this->getImportNameByFieldName('active'));
//        //Name
//        $this->assertSame(3, $this->getImportValueByFieldName('name'));
//        $this->assertSame('Name', $this->getImportNameByFieldName('name'));
//        //Categories (x,y,z...)
//        $this->assertSame(4, $this->getImportValueByFieldName('categories_names'));
//        $this->assertSame('Categories (x y z...)', $this->getImportNameByFieldName('categories_names'));
//        //Price tax excluded
//        $this->assertSame(5, $this->getImportValueByFieldName('price_tex'));
//        $this->assertSame('Price tax excluded', $this->getImportNameByFieldName('price_tex'));
//        //Price tax included
//        $this->assertSame(6, $this->getImportValueByFieldName('price_tax'));
//        $this->assertSame('Price tax included', $this->getImportNameByFieldName('price_tax'));
//        //Tax rule ID
//        $this->assertSame(7, $this->getImportValueByFieldName('id_tax_rules_group'));
//        $this->assertSame('Tax rule ID', $this->getImportNameByFieldName('id_tax_rules_group'));
//        //Cost price
//        $this->assertSame(8, $this->getImportValueByFieldName('wholesale_price'));
//        $this->assertSame('Cost price', $this->getImportNameByFieldName('wholesale_price'));
//        //On sale (0/1)
//        $this->assertSame(9, $this->getImportValueByFieldName('on_sale'));
//        $this->assertSame('On sale (0/1)', $this->getImportNameByFieldName('on_sale'));
//        //Discount amount
//        $this->assertSame(10, $this->getImportValueByFieldName('discount_amount'));
//        $this->assertSame('Discount amount', $this->getImportNameByFieldName('discount_amount'));
//        //Discount percent
//        $this->assertSame(11, $this->getImportValueByFieldName('discount_percent'));
//        $this->assertSame('Discount percent', $this->getImportNameByFieldName('discount_percent'));
//        //Discount from (yyyy-mm-dd)
//        $this->assertSame(12, $this->getImportValueByFieldName('from'));
//        $this->assertSame('Discount from (yyyy-mm-dd)', $this->getImportNameByFieldName('from'));
//        //Discount to (yyyy-mm-dd)
//        $this->assertSame(13, $this->getImportValueByFieldName('to'));
//        $this->assertSame('Discount to (yyyy-mm-dd)', $this->getImportNameByFieldName('to'));
//        //Reference #
//        $this->assertSame(14, $this->getImportValueByFieldName('reference'));
//        $this->assertSame('Reference #', $this->getImportNameByFieldName('reference'));
//        //Supplier reference #
//        $this->assertSame(15, $this->getImportValueByFieldName('supplier_reference'));
//        $this->assertSame('Supplier reference #', $this->getImportNameByFieldName('supplier_reference'));
//        //Supplier
//        $this->assertSame(16, $this->getImportValueByFieldName('supplier_name'));
//        $this->assertSame('Supplier', $this->getImportNameByFieldName('supplier_name'));
//        //Brand
//        $this->assertSame(17, $this->getImportValueByFieldName('manufacturer_name'));
//        $this->assertSame('Brand', $this->getImportNameByFieldName('manufacturer_name'));
//        //EAN13
//        $this->assertSame(18, $this->getImportValueByFieldName('ean13'));
//        $this->assertSame('EAN13', $this->getImportNameByFieldName('ean13'));
//        //UPC
//        $this->assertSame(19, $this->getImportValueByFieldName('upc'));
//        $this->assertSame('UPC', $this->getImportNameByFieldName('upc'));
//        //MPN
//        $this->assertSame(20, $this->getImportValueByFieldName('mpn'));
//        $this->assertSame('MPN', $this->getImportNameByFieldName('mpn'));
//        //Ecotax
//        $this->assertSame(21, $this->getImportValueByFieldName('ecotax'));
//        $this->assertSame('Ecotax', $this->getImportNameByFieldName('ecotax'));
//        //Width
//        $this->assertSame(22, $this->getImportValueByFieldName('width'));
//        $this->assertSame('Width', $this->getImportNameByFieldName('width'));
//        //Height
//        $this->assertSame(23, $this->getImportValueByFieldName('height'));
//        $this->assertSame('Height', $this->getImportNameByFieldName('height'));
//        //Depth
//        $this->assertSame(24, $this->getImportValueByFieldName('depth'));
//        $this->assertSame('Depth', $this->getImportNameByFieldName('depth'));
//        //Weight
//        $this->assertSame(25, $this->getImportValueByFieldName('weight'));
//        $this->assertSame('Weight', $this->getImportNameByFieldName('weight'));
//        //Delivery time of in-stock products:
//        $this->assertSame(26, $this->getImportValueByFieldName('delivery_in_stock'));
//        $this->assertSame('Delivery time of in-stock products', $this->getImportNameByFieldName('delivery_in_stock'));
//        //Delivery time of out-of-stock products with allowed orders:
//        $this->assertSame(27, $this->getImportValueByFieldName('delivery_out_stock'));
//        $this->assertSame('Delivery time of out-of-stock products with allowed orders', $this->getImportNameByFieldName('delivery_out_stock'));
//        //Quantity
//        $this->assertSame(28, $this->getImportValueByFieldName('quantity'));
//        $this->assertSame('Quantity', $this->getImportNameByFieldName('quantity'));
//        //Minimal quantity
//        $this->assertSame(29, $this->getImportValueByFieldName('minimal_quantity'));
//        $this->assertSame('Minimal quantity', $this->getImportNameByFieldName('minimal_quantity'));
//        //Low stock level
//        $this->assertSame(30, $this->getImportValueByFieldName('low_stock_threshold'));
//        $this->assertSame('Low stock level', $this->getImportNameByFieldName('low_stock_threshold'));
//        //Send me an email when the quantity is under this level
//        $this->assertSame(31, $this->getImportValueByFieldName('low_stock_alert'));
//        $this->assertSame('Send me an email when the quantity is under this level', $this->getImportNameByFieldName('low_stock_alert'));
//        //Visibility
//        $this->assertSame(32, $this->getImportValueByFieldName('visibility'));
//        $this->assertSame('Visibility', $this->getImportNameByFieldName('visibility'));
//        //Additional shipping cost
//        $this->assertSame(33, $this->getImportValueByFieldName('additional_shipping_cost'));
//        $this->assertSame('Additional shipping cost', $this->getImportNameByFieldName('additional_shipping_cost'));
//        //Unit for base price
//        $this->assertSame(34, $this->getImportValueByFieldName('unity'));
//        $this->assertSame('Unit for base price', $this->getImportNameByFieldName('unity'));
//        //Base price
//        $this->assertSame(35, $this->getImportValueByFieldName('unit_price'));
//        $this->assertSame('Base price', $this->getImportNameByFieldName('unit_price'));
//        //Summary
//        $this->assertSame(36, $this->getImportValueByFieldName('description_short'));
//        $this->assertSame('Summary', $this->getImportNameByFieldName('description_short'));
//        //Description
//        $this->assertSame(37, $this->getImportValueByFieldName('description'));
//        $this->assertSame('Description', $this->getImportNameByFieldName('description'));
//        //Tags (x,y,z...)
//        $this->assertSame(38, $this->getImportValueByFieldName('tags'));
//        $this->assertSame('Tags (x y z...)', $this->getImportNameByFieldName('tags'));
//        //Meta title
//        $this->assertSame(39, $this->getImportValueByFieldName('meta_title'));
//        $this->assertSame('Meta title', $this->getImportNameByFieldName('meta_title'));
//        //Meta keywords
//        $this->assertSame(40, $this->getImportValueByFieldName('meta_keywords'));
//        $this->assertSame('Meta keywords', $this->getImportNameByFieldName('meta_keywords'));
//        //Meta description
//        $this->assertSame(41, $this->getImportValueByFieldName('meta_description'));
//        $this->assertSame('Meta description', $this->getImportNameByFieldName('meta_description'));
//        //Rewritten URL
//        $this->assertSame(42, $this->getImportValueByFieldName('link_rewrite'));
//        $this->assertSame('Rewritten URL', $this->getImportNameByFieldName('link_rewrite'));
//        //Label when in stock
//        $this->assertSame(43, $this->getImportValueByFieldName('available_now'));
//        $this->assertSame('Label when in stock', $this->getImportNameByFieldName('available_now'));
//        //Label when backorder allowed
//        $this->assertSame(44, $this->getImportValueByFieldName('available_later'));
//        $this->assertSame('Label when backorder allowed', $this->getImportNameByFieldName('available_later'));
//        //Available for order (0 = No, 1 = Yes)
//        $this->assertSame(45, $this->getImportValueByFieldName('available_for_order'));
//        $this->assertSame('Available for order (0 = No, 1 = Yes)', $this->getImportNameByFieldName('available_for_order'));
//        //              Product availability date
//        $this->assertSame(46, $this->getImportValueByFieldName('available_date'));
//        $this->assertSame('Product availability date', $this->getImportNameByFieldName('available_date'));
//        //Product creation date
//        $this->assertSame(47, $this->getImportValueByFieldName('date_add'));
//        $this->assertSame('Product creation date', $this->getImportNameByFieldName('date_add'));
//        //Show price (0 = No, 1 = Yes)
//        $this->assertSame(48, $this->getImportValueByFieldName('show_price'));
//        $this->assertSame('Show price (0 = No 1 = Yes)', $this->getImportNameByFieldName('show_price'));
//        //Image URLs (x,y,z...)
//        $this->assertSame(49, $this->getImportValueByFieldName('image'));
//        $this->assertSame('Image URLs (x y z...)', $this->getImportNameByFieldName('image'));
//        //Image alt texts (x,y,z...)
//        $this->assertSame(50, $this->getImportValueByFieldName('image_alt'));
//        $this->assertSame('Image alt texts (x y z...)', $this->getImportNameByFieldName('image_alt'));
//        //Delete existing images (0 = No, 1 = Yes)
////        $this->assertSame(51, $this->getImportValueByFieldName('active'));
////        $this->assertSame('Active (0/1)', $this->getImportNameByFieldName('id_product'));
//        //Feature (Name:Value:Position:Customized)
//        $this->assertSame(52, $this->getImportValueByFieldName('features'));
//        $this->assertSame('Feature (Name:Value:Position:Customized)', $this->getImportNameByFieldName('features'));
//        //Available online only (0 = No, 1 = Yes)
//        $this->assertSame(53, $this->getImportValueByFieldName('online_only'));
//        $this->assertSame('Available online only (0 = No 1 = Yes)', $this->getImportNameByFieldName('online_only'));
//        //Condition
//        $this->assertSame(54, $this->getImportValueByFieldName('condition'));
//        $this->assertSame('Condition', $this->getImportNameByFieldName('condition'));
//        //Customizable (0 = No, 1 = Yes)
//        $this->assertSame(55, $this->getImportValueByFieldName('customizable'));
//        $this->assertSame('Customizable (0 = No 1 = Yes)', $this->getImportNameByFieldName('customizable'));
//        //Uploadable files (0 = No, 1 = Yes)
//        $this->assertSame(56, $this->getImportValueByFieldName('uploadable_files'));
//        $this->assertSame('Uploadable files (0 = No 1 = Yes)', $this->getImportNameByFieldName('uploadable_files'));
//        //Text fields (0 = No, 1 = Yes)
//        $this->assertSame(57, $this->getImportValueByFieldName('text_fields'));
//        $this->assertSame('Text fields (0 = No 1 = Yes)', $this->getImportNameByFieldName('text_fields'));
//        //Action when out of stock
//        $this->assertSame(58, $this->getImportValueByFieldName('out_of_stock'));
//        $this->assertSame('Action when out of stock', $this->getImportNameByFieldName('out_of_stock'));
//        //Virtual product (0 = No, 1 = Yes)
//        $this->assertSame(59, $this->getImportValueByFieldName('is_virtual'));
//        $this->assertSame('Virtual product (0 = No 1 = Yes)', $this->getImportNameByFieldName('is_virtual'));
//        //File URL
//        $this->assertSame(60, $this->getImportValueByFieldName('file_url'));
//        $this->assertSame('File URL', $this->getImportNameByFieldName('file_url'));
//        //Number of allowed downloads
//        $this->assertSame(61, $this->getImportValueByFieldName('nb_downloadable'));
//        $this->assertSame('Number of allowed downloads', $this->getImportNameByFieldName('nb_downloadable'));
//        //Expiration date (yyyy-mm-dd)
//        $this->assertSame(62, $this->getImportValueByFieldName('date_expiration'));
//        $this->assertSame('Expiration date (yyyy-mm-dd)', $this->getImportNameByFieldName('date_expiration'));
//        //Number of days
//        $this->assertSame(63, $this->getImportValueByFieldName('nb_days_accessible'));
//        $this->assertSame('Number of days', $this->getImportNameByFieldName('nb_days_accessible'));
//        //ID / Name of shop
////        $this->assertSame(64, $this->getImportValueByFieldName('shop')); // todo check this field
////        $this->assertSame('ID / Name of shop', $this->getImportNameByFieldName('shop'));
//        //Advanced Stock Management
//        $this->assertSame(65, $this->getImportValueByFieldName('advanced_stock_management'));
//        $this->assertSame('Advanced Stock Management', $this->getImportNameByFieldName('advanced_stock_management'));
//        //Depends on stock
//        $this->assertSame(66, $this->getImportValueByFieldName('depends_on_stock'));
//        $this->assertSame('Depends on stock', $this->getImportNameByFieldName('depends_on_stock'));
//        //Warehouse
//        $this->assertSame(67, $this->getImportValueByFieldName('warehouse'));
//        $this->assertSame('Warehouse', $this->getImportNameByFieldName('warehouse'));
//        //Accessories (x,y,z...)
//        $this->assertSame(68, $this->getImportValueByFieldName('accessories'));
//        $this->assertSame('Accessories (x y z...)', $this->getImportNameByFieldName('accessories'));
//    }
//
//    public function test_Import_Product_Fields_Check_Duplicates()
//    {
//        foreach ($this->ae->products as $value) {
//            if (isset($value['import'])) {
//                $times = 0;
//                foreach ($this->ae->products as $sub_value) {
//                    if (isset($sub_value['import'])) {
//                        if ($sub_value['import'] == $value['import']) {
//                            $times += 1;
//                        }
//                    }
//                }
//
//                $this->assertSame(1, $times, 'Error import ' . $value['field'] .
//                    ' with value ' . $value['import'] . ' exists ' . $times . ' times');
//            }
//
//
//        }
//    }
//
//    public function test_Import_Combination_Fields()
//    {
//        //Product ID
//        $this->assertSame(1, $this->getImportCombinationValueByFieldName('id_product'));
//        $this->assertSame('Product ID', $this->getImportCombinationNameByFieldName('id_product'));
//        //Product reference
////        $this->assertSame(2, $this->getImportCombinationValueByFieldName('reference'));
////        $this->assertSame('Product reference', $this->getImportCombinationNameByFieldName('reference'));
//        //Attribute (Name:Type:Position)*
//        $this->assertSame(3, $this->getImportCombinationValueByFieldName('combination_attributes_name'));
//        $this->assertSame('Attribute (Name:Type:Position)*', $this->getImportCombinationNameByFieldName('combination_attributes_name'));
//        //Value (Value:Position)*
//        $this->assertSame(4, $this->getImportCombinationValueByFieldName('combination_attributes_value'));
//        $this->assertSame('Value (Value:Position)*', $this->getImportCombinationNameByFieldName('combination_attributes_value'));
//        //Supplier reference
//        $this->assertSame(5, $this->getImportCombinationValueByFieldName('combination_supplier_reference'));
//        $this->assertSame('Supplier reference', $this->getImportCombinationNameByFieldName('combination_supplier_reference'));
//        //Reference
//        $this->assertSame(6, $this->getImportCombinationValueByFieldName('combination_reference'));
//        $this->assertSame('Reference', $this->getImportCombinationNameByFieldName('combination_reference'));
//        //EAN13
//        $this->assertSame(7, $this->getImportCombinationValueByFieldName('combination_ean13'));
//        $this->assertSame('EAN13', $this->getImportCombinationNameByFieldName('combination_ean13'));
//        //UPC
//        $this->assertSame(8, $this->getImportCombinationValueByFieldName('combination_upc'));
//        $this->assertSame('UPC', $this->getImportCombinationNameByFieldName('combination_upc'));
//        //MPN
//        $this->assertSame(9, $this->getImportCombinationValueByFieldName('combination_mpn'));
//        $this->assertSame('MPN', $this->getImportCombinationNameByFieldName('combination_mpn'));
//        //Cost price
//        $this->assertSame(10, $this->getImportCombinationValueByFieldName('combination_wholesale_price'));
//        $this->assertSame('Cost price', $this->getImportCombinationNameByFieldName('combination_wholesale_price'));
//        //Impact on price
//        $this->assertSame(11, $this->getImportCombinationValueByFieldName('combination_price'));
//        $this->assertSame('Impact on price', $this->getImportCombinationNameByFieldName('combination_price'));
//        //Ecotax
//        $this->assertSame(12, $this->getImportCombinationValueByFieldName('combination_ecotax'));
//        $this->assertSame('Ecotax', $this->getImportCombinationNameByFieldName('combination_ecotax'));
//        //Quantity
//        $this->assertSame(13, $this->getImportCombinationValueByFieldName('combination_quantity'));
//        $this->assertSame('Quantity', $this->getImportCombinationNameByFieldName('combination_quantity'));
//        //Minimal quantity
//        $this->assertSame(14, $this->getImportCombinationValueByFieldName('combination_minimal_quantity'));
//        $this->assertSame('Minimal quantity', $this->getImportCombinationNameByFieldName('combination_minimal_quantity'));
//        //Low stock level
//        $this->assertSame(15, $this->getImportCombinationValueByFieldName('combination_low_stock_threshold'));
//        $this->assertSame('Low stock level', $this->getImportCombinationNameByFieldName('combination_low_stock_threshold'));
//        //Send me an email when the quantity is under this level
//        $this->assertSame(16, $this->getImportCombinationValueByFieldName('combination_low_stock_alert'));
//        $this->assertSame('Send me an email when the quantity is under this level', $this->getImportCombinationNameByFieldName('combination_low_stock_alert'));
//        //Impact on weight
//        $this->assertSame(17, $this->getImportCombinationValueByFieldName('combination_weight'));
//        $this->assertSame('Impact on weight', $this->getImportCombinationNameByFieldName('combination_weight'));
//        //Default (0 = No, 1 = Yes)
//        $this->assertSame(18, $this->getImportCombinationValueByFieldName('combination_default_combination'));
//        $this->assertSame('Default (0 = No 1 = Yes)', $this->getImportCombinationNameByFieldName('combination_default_combination'));
//        //Combination availability date
//        $this->assertSame(19, $this->getImportCombinationValueByFieldName('combination_available_date'));
//        $this->assertSame('Combination availability date', $this->getImportCombinationNameByFieldName('combination_available_date'));
//        //Choose among product images by position (1,2,3...)
//        $this->assertSame(20, $this->getImportCombinationValueByFieldName('combination_image_position'));
//        $this->assertSame('Choose among product images by position (1 2 3...)', $this->getImportCombinationNameByFieldName('combination_image_position'));
//        //Image URLs (x,y,z...)
//        $this->assertSame(21, $this->getImportCombinationValueByFieldName('combination_images'));
//        $this->assertSame('Image URLs (x y z...)', $this->getImportCombinationNameByFieldName('combination_images'));
//        //Image alt texts (x,y,z...)
//        $this->assertSame(22, $this->getImportCombinationValueByFieldName('combination_image_alt'));
//        $this->assertSame('Image alt texts (x y z...)', $this->getImportCombinationNameByFieldName('combination_image_alt'));
//        //ID / Name of shop
////        $this->assertSame(23, $this->getImportCombinationValueByFieldName('id_product'));
////        $this->assertSame('ID / Name of shop', $this->getImportCombinationNameByFieldName('id_product'));
//        //Advanced Stock Management
//        $this->assertSame(24, $this->getImportCombinationValueByFieldName('advanced_stock_management'));
//        $this->assertSame('Advanced Stock Management', $this->getImportCombinationNameByFieldName('advanced_stock_management'));
//        //Depends on stock
//        $this->assertSame(25, $this->getImportCombinationValueByFieldName('depends_on_stock'));
//        $this->assertSame('Depends on stock', $this->getImportCombinationNameByFieldName('depends_on_stock'));
//        //Warehouse
//        $this->assertSame(26, $this->getImportCombinationValueByFieldName('combination_warehouse'));
//        $this->assertSame('Warehouse', $this->getImportCombinationNameByFieldName('combination_warehouse'));
//    }
//
//    public function teardown()
//    {
//    }
//
//    public function getImportValueByFieldName($field)
//    {
//        foreach ($this->ae->products as $key => $value) {
//            if ($value['field'] === $field) {
//                return $value['import'];
//            }
//        }
//    }
//
//    public function getImportCombinationValueByFieldName($field)
//    {
//        foreach ($this->ae->products as $key => $value) {
//            if ($value['field'] === $field) {
//                return $value['import_combination'];
//            }
//        }
//    }
//
//    public function getImportNameByFieldName($field)
//    {
//        foreach ($this->ae->products as $key => $value) {
//            if ($value['field'] === $field) {
//                return $value['import_name'];
//            }
//        }
//    }
//
//    public function getImportCombinationNameByFieldName($field)
//    {
//        foreach ($this->ae->products as $key => $value) {
//            if ($value['field'] === $field) {
//                return $value['import_combination_name'];
//            }
//        }
//    }

    /**
     * Test for displayTemplate
     */
    public function testDisplayTemplate_Should_Return_Template_Path()
    {
        //arrange
        $template = 'admin/test.tpl';
        $mock = $this->createPartialMock('' . self::ADVANCED_EXPORT . '', array('display'));
        $mock->expects($this->once())
            ->method('display')
            ->will($this->returnValue($template));

        //act
        $path = $mock->displayTemplate($template);

        //assert
        $this->assertSame('admin/test.tpl', $path);
    }


}
