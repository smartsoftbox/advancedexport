<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

include_once 'BaseFields.php';

class ProductsFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'Product Id',
            'field' => 'id_product',
            'database' => 'products',
            'import' => 1,
            'import_name' => 'ID',
            'import_combination' => 1,
            'import_combination_name' => 'Product ID',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Product Reference',
            'field' => 'reference',
            'database' => 'products',
            'import' => 14,
            'import_name' => 'Reference #',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Name',
            'field' => 'name',
            'database' => 'products_lang',
            'import' => 3,
            'import_name' => 'Name',
            'alias' => 'pl',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Short Description',
            'field' => 'description_short',
            'database' => 'products_lang',
            'import' => 36,
            'import_name' => 'Summary',
            'alias' => 'pl',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Long Description',
            'field' => 'description',
            'database' => 'products_lang',
            'import' => 37,
            'import_name' => 'Description',
            'alias' => 'pl',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Quantity',
            'field' => 'quantity',
            'database' => 'other',
            'import' => 28,
            'import_name' => 'Quantity',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Price',
            'field' => 'price',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Price Catalogue TTC',
            'field' => 'price_tax_nodiscount',
            'database' => 'other',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Price Tax',
            'field' => 'price_tax',
            'database' => 'other',
            'import' => 6,
            'import_name' => 'Price tax included',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Wholesale Price',
            'field' => 'wholesale_price',
            'database' => 'products',
            'import' => 8,
            'import_name' => 'Cost price',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Supplier Id (default)',
            'field' => 'id_supplier',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::SUPPLIERS
        ),
        array(
            'name' => 'Suppliers Ids',
            'field' => 'id_supplier_all',
            'database' => 'other',
            'group15' => ProductGroup::SUPPLIERS
        ),
        array(
            'name' => 'Supplier Name (default)',
            'field' => 'supplier_name',
            'as' => true,
            'database' => 'supplier',
            'import' => 16,
            'import_name' => 'Supplier',
            'alias' => 's',
            'group15' => ProductGroup::SUPPLIERS
        ),
        array(
            'name' => 'Supplier Names',
            'field' => 'supplier_name_all',
            'database' => 'other',
            'group15' => ProductGroup::SUPPLIERS
        ),
        array(
            'name' => 'Manufacturer Id',
            'field' => 'id_manufacturer',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'Manufacturer Name',
            'field' => 'manufacturer_name',
            'database' => 'other',
            'import' => 17,
            'import_name' => 'Brand',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'Tax Id Rules Group',
            'field' => 'id_tax_rules_group',
            'database' => 'products',
            'import' => 7,
            'import_name' => 'Tax rule ID',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Tax Rate',
            'field' => 'tax_rate',
            'database' => 'other',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Default Category Id',
            'field' => 'id_category_default',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'Default Category Name',
            'field' => 'nameCategoryDefault',
            'database' => 'other',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'Categories Names',
            'field' => 'categories_names',
            'database' => 'other',
            'import' => 4,
            'import_name' => 'Categories (x y z...)',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'Categories Ids',
            'field' => 'categories_ids',
            'database' => 'other',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'On Sale',
            'field' => 'on_sale',
            'database' => 'products',
            'import' => 9,
            'import_name' => 'On sale (0/1)',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'EAN 13',
            'field' => 'ean13',
            'database' => 'products',
            'alias' => 'p',
            'import' => 18,
            'import_name' => 'EAN13',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Supplier Reference',
            'field' => 'supplier_reference',
            'database' => 'other',
            'import' => 15,
            'import_name' => 'Supplier reference #',
            'group15' => ProductGroup::SUPPLIERS
        ),
        array(
            'name' => 'Date Added',
            'field' => 'date_add',
            'database' => 'products',
            'import' => 47,
            'import_name' => 'Product creation date',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Date Update',
            'field' => 'date_upd',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Active',
            'field' => 'active',
            'database' => 'products',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Meta Title',
            'field' => 'meta_title',
            'database' => 'products_lang',
            'import' => 39,
            'import_name' => 'Meta title',
            'alias' => 'pl',
            'group15' => ProductGroup::SEO
        ),
        array(
            'name' => 'Meta Description',
            'field' => 'meta_description',
            'database' => 'products_lang',
            'import' => 41,
            'import_name' => 'Meta description',
            'alias' => 'pl',
            'group15' => ProductGroup::SEO
        ),
        array(
            'name' => 'Meta Keywords',
            'field' => 'meta_keywords',
            'database' => 'products_lang',
            'import' => 40,
            'import_name' => 'Meta keywords',
            'alias' => 'pl',
            'group15' => ProductGroup::SEO
        ),
        array(
            'name' => 'Available Now',
            'field' => 'available_now',
            'database' => 'products_lang',
            'import' => 43,
            'import_name' => 'Label when in stock',
            'alias' => 'pl',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Available Later',
            'field' => 'available_later',
            'database' => 'products_lang',
            'import' => 44,
            'import_name' => 'Label when backorder allowed',
            'alias' => 'pl',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Tags',
            'field' => 'tags',
            'database' => 'other',
            'import' => 38,
            'import_name' => 'Tags (x y z...)',
            'group15' => ProductGroup::SEO
        ),
        array(
            'name' => 'Accessories',
            'field' => 'accessories',
            'database' => 'other',
            'import' => 68,
            'import_name' => 'Accessories (x y z...)',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'Images',
            'field' => 'images',
            'database' => 'other',
            'group15' => ProductGroup::IMAGES
        ),
        array(
            'name' => 'Online only',
            'field' => 'online_only',
            'database' => 'products',
            'import' => 53,
            'import_name' => 'Available online only (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Upc',
            'field' => 'upc',
            'database' => 'products',
            'import' => 19,
            'import_name' => 'UPC',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Ecotax',
            'field' => 'ecotax',
            'database' => 'products',
            'import' => 21,
            'import_name' => 'Ecotax',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Unity',
            'field' => 'unity',
            'database' => 'products',
            'import' => 34,
            'import_name' => 'Unit for base price',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Unit Price Ratio',
            'field' => 'unit_price_ratio',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Minimal Quantity',
            'field' => 'minimal_quantity',
            'database' => 'products',
            'import' => 29,
            'import_name' => 'Minimal quantity',
            'alias' => 'p',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Additional Shipping Cost',
            'field' => 'additional_shipping_cost',
            'database' => 'products',
            'import' => 33,
            'import_name' => 'Additional shipping cost',
            'alias' => 'p',
            'group15' => ProductGroup::SHIPPING
        ),
        array(
            'name' => 'Location',
            'field' => 'location',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Width',
            'field' => 'width',
            'database' => 'products',
            'import' => 22,
            'import_name' => 'Width',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Height',
            'field' => 'height',
            'database' => 'products',
            'import' => 23,
            'import_name' => 'Height',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Depth',
            'field' => 'depth',
            'database' => 'products',
            'import' => 24,
            'import_name' => 'Depth',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Weight',
            'field' => 'weight',
            'database' => 'products',
            'import' => 25,
            'import_name' => 'Weight',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Out Of Stock',
            'field' => 'out_of_stock',
            'database' => 'products',
            'import' => 58,
            'import_name' => 'Action when out of stock',
            'alias' => 'p',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Quantity Discount',
            'field' => 'quantity_discount',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Customizable',
            'field' => 'customizable',
            'database' => 'products',
            'import' => 55,
            'import_name' => 'Customizable (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::CUSTOMIZATION
        ),
        array(
            'name' => 'Uploadable Files',
            'field' => 'uploadable_files',
            'database' => 'products',
            'import' => 56,
            'import_name' => 'Uploadable files (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::CUSTOMIZATION
        ),
        array(
            'name' => 'Text Fields',
            'field' => 'text_fields',
            'database' => 'products',
            'import' => 57,
            'import_name' => 'Text fields (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::CUSTOMIZATION
        ),
        array(
            'name' => 'Available For Order',
            'field' => 'available_for_order',
            'database' => 'products',
            'import' => 45,
            'import_name' => 'Available for order (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Condition',
            'field' => 'condition',
            'database' => 'products',
            'import' => 54,
            'import_name' => 'Condition',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Show Price',
            'field' => 'show_price',
            'database' => 'products',
            'import' => 48,
            'import_name' => 'Show price (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Indexed',
            'field' => 'indexed',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Cache Is Pack',
            'field' => 'cache_is_pack',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Cache Has Attachments',
            'field' => 'cache_has_attachments',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::ATTACHMENTS
        ),
        array(
            'name' => 'Cache Default Attribute',
            'field' => 'cache_default_attribute',
            'database' => 'products',
            'alias' => 'p',
            'group15' => ProductGroup::ATTACHMENTS
        ),
        array(
            'name' => 'Link Rewrite',
            'field' => 'link_rewrite',
            'database' => 'products_lang',
            'import' => 42,
            'import_name' => 'Rewritten URL',
            'alias' => 'pl',
            'group15' => ProductGroup::SEO
        ),
        array(
            'name' => 'Url Product',
            'field' => 'url_product',
            'database' => 'other',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Features',
            'field' => 'features',
            'database' => 'other',
            'import' => 52,
            'import_name' => 'Feature (Name:Value:Position:Customized)',
            'group15' => ProductGroup::FEATURES
        ),
        array(
            'name' => 'Visibility',
            'field' => 'visibility',
            'database' => 'products',
            'import' => 32,
            'import_name' => 'Visibility',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Product available date',
            'field' => 'available_date',
            'database' => 'products',
            'import' => 46,
            'import_name' => 'Product availability date',
            'alias' => 'p',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Discount amount',
            'field' => 'discount_amount',
            'database' => 'specific_price',
            'import' => 10,
            'import_name' => 'Discount amount',
            'alias' => 'sp_tmp',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Discount percent',
            'field' => 'discount_percent',
            'database' => 'specific_price',
            'import' => 11,
            'import_name' => 'Discount percent',
            'alias' => 'sp_tmp',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Discount from (yyyy-mm-dd)',
            'field' => 'from',
            'database' => 'specific_price',
            'import' => 12,
            'import_name' => 'Discount from (yyyy-mm-dd)',
            'alias' => 'sp_tmp',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Discount to (yyyy-mm-dd)',
            'field' => 'to',
            'database' => 'specific_price',
            'import' => 13,
            'import_name' => 'Discount to (yyyy-mm-dd)',
            'alias' => 'sp_tmp',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Cover',
            'field' => 'image',
            'database' => 'other',
            'import' => 49,
            'import_name' => 'Image URLs (x y z...)',
            'group15' => ProductGroup::IMAGES
        ),
        array(
            'name' => 'Id shop default',
            'field' => 'id_shop_default',
            'database' => 'products',
            'import' => 64,
            'import_name' => 'ID / Name of shop',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Advanced stock management',
            'field' => 'advanced_stock_management',
            'database' => 'products',
            'import' => 65,
            'import_name' => 'Advanced Stock Management',
            'import_combination' => 24,
            'import_combination_name' => 'Advanced Stock Management',
            'alias' => 'p',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Depends On Stock',
            'field' => 'depends_on_stock',
            'database' => 'other',
            'import' => 66,
            'import_name' => 'Depends on stock',
            'import_combination' => 25,
            'import_combination_name' => 'Depends on stock',
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Warehouse',
            'field' => 'warehouse',
            'database' => 'other',
            'import' => 67,
            'import_name' => 'Warehouse',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Image alt',
            'field' => 'image_alt',
            'database' => 'other',
            'import' => 50,
            'import_name' => 'Image alt texts (x y z...)',
            'group15' => ProductGroup::IMAGES
        ),
        array(
            'name' => 'Image position',
            'field' => 'image_position',
            'database' => 'other',
//            'import' => 15,
//            'import_name' => 'Image position',
            'group15' => ProductGroup::IMAGES
        ),
        array(
            'name' => 'Product attachments url',
            'field' => 'attachments',
            'database' => 'other',
            'group15' => ProductGroup::ATTACHMENTS
        ),
        array(
            'name' => 'Is Virtual',
            'field' => 'is_virtual',
            'database' => 'products',
            'import' => 59,
            'import_name' => 'Virtual product (0 = No 1 = Yes)',
            'alias' => 'p',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'NB Downloadable',
            'field' => 'nb_downloadable',
            'database' => 'products',
            'import' => 61,
            'import_name' => 'Number of allowed downloads',
            'alias' => 'pd',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Date Expiration',
            'field' => 'date_expiration',
            'database' => 'products',
            'import' => 62,
            'import_name' => 'Expiration date (yyyy-mm-dd)',
            'alias' => 'pd',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Nb Days Accessible',
            'field' => 'nb_days_accessible',
            'database' => 'products',
            'import' => 63,
            'import_name' => 'Number of days',
            'alias' => 'pd',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'File URL',
            'field' => 'file_url',
            'database' => 'other',
            'import' => 60,
            'import_name' => 'File URL',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'field' => 'delivery_in_stock',
            'database' => 'products_lang',
            'name' => 'Delivery In Stock',
            'import' => 26,
            'import_name' => 'Delivery time of in-stock products',
            'alias' => 'pl',
            'version' => 1.7,
            'group15' => ProductGroup::SHIPPING
        ),
        array(
            'field' => 'delivery_out_stock',
            'database' => 'products_lang',
            'name' => 'Delivery Out Stock',
            'import' => 27,
            'import_name' => 'Delivery time of out-of-stock products with allowed orders',
            'alias' => 'pl',
            'version' => 1.7,
            'group15' => ProductGroup::SHIPPING
        ),
        array(
            'field' => 'low_stock_threshold',
            'database' => 'products',
            'name' => 'Low Stock Threshold',
            'import' => 30,
            'import_name' => 'Low stock level',
            'alias' => 'product_shop',
            'version' => 1.7,
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'field' => 'low_stock_alert',
            'database' => 'products',
            'name' => 'Low Stock Alert',
            'import' => 31,
            'import_name' => 'Send me an email when the quantity is under this level',
            'alias' => 'product_shop',
            'version' => 1.7,
            'group15' => ProductGroup::QUANTITIES
        ),
        array(
            'name' => 'Categories Path',
            'field' => 'categories_path',
            'database' => 'other',
            'group15' => ProductGroup::ASSOCIATIONS
        ),
        array(
            'name' => 'MPN',
            'field' => 'mpn',
            'database' => 'products',
            'import' => 20,
            'import_name' => 'MPN',
            'import_combination' => 0, // without this error in insert
            'alias' => 'p',
            'version' => '1.7.7',
            'group15' => ProductGroup::INFORMATION
        ),
        array(
            'name' => 'Price Tax excluded',
            'field' => 'price_tex',
            'database' => 'other',
            'import' => 5,
            'import_name' => 'Price tax excluded',
            'group15' => ProductGroup::PRICES
        ),
        array(
            'name' => 'Unit Price',
            'field' => 'unit_price',
            'database' => 'other',
            'import' => 35,
            'import_name' => 'Base price',
            'alias' => 'p',
            'group15' => ProductGroup::PRICES
        ),
        //combination xxxx
        array(
            'name' => 'Combination Reference',
            'field' => 'combination_reference',
            'import_combination' => 6,
            'import_combination_name' => 'Reference',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Quantity',
            'field' => 'combination_quantity',
            'import_combination' => 13,
            'import_combination_name' => 'Quantity',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Price',
            'field' => 'combination_price',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Impact on Price',
            'field' => 'combination_impact_price',
            'import_combination' => 11,
            'import_combination_name' => 'Impact on price',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Price Catalogue TTC',
            'field' => 'combination_price_tax_nodiscount',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Price Tax',
            'field' => 'combination_price_tax',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Wholesale Price',
            'field' => 'combination_wholesale_price',
            'database' => '',
            'import_combination' => 10,
            'import_combination_name' => 'Cost price',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination MPN',
            'field' => 'combination_mpn',
            'database' => '',
            'import_combination' => 9,
            'import_combination_name' => 'MPN',
            'attribute' => true,
            'version' => '1.7.7',
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Suppliers Ids',
            'field' => 'combination_id_supplier_all',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Supplier Names',
            'field' => 'combination_supplier_name_all',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination EAN 13',
            'field' => 'combination_ean13',
            'database' => '',
            'import_combination' => 7,
            'import_combination_name' => 'EAN13',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Supplier Reference',
            'field' => 'combination_supplier_reference',
            'database' => '',
            'import_combination' => 5,
            'import_combination_name' => 'Supplier reference',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Images',
            'field' => 'combination_images',
            'database' => '',
            'attribute' => true,
            'import_combination' => 21,
            'import_combination_name' => 'Image URLs (x y z...)',
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Upc',
            'field' => 'combination_upc',
            'database' => '',
            'import_combination' => 8,
            'import_combination_name' => 'UPC',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Ecotax',
            'field' => 'combination_ecotax',
            'database' => '',
            'import_combination' => 12,
            'import_combination_name' => 'Ecotax',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Minimal Quantity',
            'field' => 'combination_minimal_quantity',
            'database' => '',
            'import_combination' => 14,
            'import_combination_name' => 'Minimal quantity',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Location',
            'field' => 'combination_location',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Weight',
            'field' => 'combination_weight',
            'database' => '',
            'import_combination' => 17,
            'import_combination_name' => 'Impact on weight',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Attributes',
            'field' => 'combination_attributes',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Attributes Name',
            'field' => 'combination_attributes_name',
            'database' => '',
            'attribute' => true,
            'import_combination' => 3,
            'import_combination_name' => 'Attribute (Name:Type:Position)*',
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Attributes Value',
            'field' => 'combination_attributes_value',
            'database' => '',
            'attribute' => true,
            'import_combination' => 4,
            'import_combination_name' => 'Value (Value:Position)*',
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination available date',
            'field' => 'combination_available_date',
            'database' => '',
            'attribute' => true,
            'import_combination' => 19,
            'import_combination_name' => 'Combination availability date',
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Image alt',
            'field' => 'combination_image_alt',
            'database' => '',
            'import_combination' => 22,
            'import_combination_name' => 'Image alt texts (x y z...)',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Image position',
            'field' => 'combination_image_position',
            'database' => '',
            'import_combination' => 20,
            'import_combination_name' => 'Choose among product images by position (1 2 3...)',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Default (0 = No 1 = Yes)',
            'field' => 'combination_default_combination',
            'database' => '',
            'import_combination' => 18,
            'import_combination_name' => 'Default (0 = No 1 = Yes)',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
        array(
            'name' => 'Combination Warehouse',
            'field' => 'combination_warehouse',
            'database' => '',
            'import_combination' => 26,
            'import_combination_name' => 'Warehouse',
            'group15' => ProductGroup::COMBINATIONS,
            'attribute' => true,
        ),
        array(
            'field' => 'combination_low_stock_threshold',
            'name' => 'Combination Low Stock Threshold',
            'database' => '',
            'import_combination' => 15,
            'import_combination_name' => 'Low stock level',
            'version' => 1.7,
            'group15' => ProductGroup::COMBINATIONS,
            'attribute' => true,
        ),
        array(
            'field' => 'combination_low_stock_alert',
            'name' => 'Combination Low Stock Alert',
            'database' => '',
            'import_combination' => 16,
            'import_combination_name' => 'Send me an email when the quantity is under this level',
            'version' => 1.7,
            'group15' => ProductGroup::COMBINATIONS,
            'attribute' => true,
        ),
        array(
            'name' => 'Combination Id Product Attribute',
            'field' => 'combination_id_product_attribute',
            'database' => '',
            'attribute' => true,
            'group15' => ProductGroup::COMBINATIONS
        ),
    );
}
