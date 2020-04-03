<?php

class Fields418
{
    public $products = array(
        array(
            'name' => 'Product Id',
            'field' => 'id_product',
            'database' => 'products',
            'import' => 1,
            'import_combination' => 1,
            'import_combination_name' => 'Product ID*',
            'import_name' => 'ID',
            'alias' => 'p'
        ),
        array(
            'name' => 'Product Reference',
            'field' => 'reference',
            'database' => 'products',
            'import' => 13,
            'import_combination' => 5,
            'import_combination_name' => 'Reference',
            'import_name' => 'Reference #',
            'alias' => 'p',
            'attribute' => true
        ),
        array(
            'name' => 'Name',
            'field' => 'name',
            'database' => 'products_lang',
            'import' => 2,
            'import_name' => 'Name *',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Short Description',
            'field' => 'description_short',
            'database' => 'products_lang',
            'import' => 30,
            'import_name' => 'Description',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Long Description',
            'field' => 'description',
            'database' => 'products_lang',
            'import' => 31,
            'import_name' => 'Short description',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Quantity',
            'field' => 'quantity',
            'database' => 'other',
            'import' => 24,
            'import_name' => 'Quantity',
            'import_combination' => 10,
            'import_combination_name' => 'Quantity',
            'attribute' => true
        ),
        array(
            'name' => 'Price',
            'field' => 'price',
            'database' => 'products',
            'alias' => 'p',
            'import_combination' => 9,
            'import_combination_name' => 'Impact on Price',
            'attribute' => true
        ),
        array(
            'name' => 'Price Catalogue TTC',
            'field' => 'price_tax_nodiscount',
            'database' => 'other',
            'attribute' => true
        ),
        array(
            'name' => 'Price Tax',
            'field' => 'price_tax',
            'database' => 'other',
            'import' => 5,
            'import_name' => 'Price tax included',
            'attribute' => true
        ),
        array(
            'name' => 'Wholesale Price',
            'field' => 'wholesale_price',
            'database' => 'products',
            'import' => 7,
            'import_combination' => 8,
            'import_name' => 'Wholesale price',
            'alias' => 'p',
            'attribute' => true
        ),
        array('name' => 'Supplier Id (default)', 'field' => 'id_supplier', 'database' => 'products', 'alias' => 'p'),
        array('name' => 'Suppliers Ids', 'field' => 'id_supplier_all', 'database' => 'other', 'attribute' => true),
        array(
            'name' => 'Supplier Name (default)',
            'field' => 'supplier_name',
            'as' => true,
            'database' => 'supplier',
            'import' => 15,
            'import_name' => 'Supplier',
            'alias' => 's'
        ),
        array('name' => 'Supplier Names', 'field' => 'supplier_name_all', 'database' => 'other', 'attribute' => true),
        array('name' => 'Manufacturer Id', 'field' => 'id_manufacturer', 'database' => 'products', 'alias' => 'p'),
        array(
            'name' => 'Manufacturer Name',
            'field' => 'manufacturer_name',
            'database' => 'other',
            'import' => 16,
            'import_name' => 'Manufacturer'
        ),
        array(
            'name' => 'Tax Id Rules Group',
            'field' => 'id_tax_rules_group',
            'database' => 'products',
            'import' => 6,
            'import_name' => 'Tax rules ID',
            'alias' => 'p'
        ),
        array('name' => 'Tax Rate', 'field' => 'tax_rate', 'database' => 'other'),
        array(
            'name' => 'Default Category Id',
            'field' => 'id_category_default',
            'database' => 'products',
            'alias' => 'p'
        ),
        array('name' => 'Default Category Name', 'field' => 'nameCategoryDefault', 'database' => 'other'),
        array('name' => 'Categories Names', 'field' => 'categories_names', 'database' => 'other'),
        array(
            'name' => 'Categories Ids',
            'field' => 'categories_ids',
            'database' => 'other',
            'import' => 4,
            'import_name' => 'Categories (x,y,z...)'
        ),
        array(
            'name' => 'On Sale',
            'field' => 'on_sale',
            'database' => 'products',
            'import' => 8,
            'import_name' => 'On sale (0/1)',
            'alias' => 'p'
        ),
        array(
            'name' => 'EAN 13',
            'field' => 'ean13',
            'database' => 'products',
            'alias' => 'p',
            'import' => 17,
            'import_combination' => 6,
            'import_combination_name' => 'EAN 13',
            'import_name' => 'EAN13',
            'attribute' => true
        ),
        array(
            'name' => 'Supplier Reference',
            'field' => 'supplier_reference',
            'database' => 'other',
            'import' => 14,
            'import_combination' => 4,
            'import_combination_name' => 'Supplier reference',
            'import_name' => 'Supplier reference #',
            'attribute' => true
        ),
        array(
            'name' => 'Date Added',
            'field' => 'date_add',
            'database' => 'products',
            'import' => 40,
            'import_name' => 'Product creation date',
            'alias' => 'p'
        ),
        array('name' => 'Date Update', 'field' => 'date_upd', 'database' => 'products', 'alias' => 'p'),
        array(
            'name' => 'Active',
            'field' => 'active',
            'database' => 'products',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'alias' => 'p'
        ),
        array(
            'name' => 'Meta Title',
            'field' => 'meta_title',
            'database' => 'products_lang',
            'import' => 33,
            'import_name' => 'Meta title',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Meta Description',
            'field' => 'meta_description',
            'database' => 'products_lang',
            'import' => 35,
            'import_name' => 'Meta description',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Meta Keywords',
            'field' => 'meta_keywords',
            'database' => 'products_lang',
            'import' => 35,
            'import_name' => 'Meta keywords',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Available Now',
            'field' => 'available_now',
            'database' => 'products_lang',
            'import' => 36,
            'import_name' => 'Text when in stock',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Available Later',
            'field' => 'available_later',
            'database' => 'products_lang',
            'import' => 37,
            'import_name' => 'Text when backorder allowed',
            'alias' => 'pl'
        ),
        array(
            'name' => 'Tags',
            'field' => 'tags',
            'database' => 'other',
            'import' => 32,
            'import_name' => 'Tags (x,y,z...)'
        ),
        array('name' => 'Accessories', 'field' => 'accessories', 'database' => 'other'),
        array(
            'name' => 'Images',
            'field' => 'images',
            'database' => 'other',
            'attribute' => true,
            'import_combination' => 16,
            'import_combination_name' => 'Image URLs (x,y,z...)'
        ),
        array(
            'name' => 'Online only',
            'field' => 'online_only',
            'database' => 'products',
            'import' => 47,
            'import_name' => 'Available online only (0 = No, 1 = Yes)',
            'alias' => 'p'
        ),
        array(
            'name' => 'Upc',
            'field' => 'upc',
            'database' => 'products',
            'import' => 18,
            'import_combination' => 7,
            'import_combination_name' => 'UPC',
            'import_name' => 'UPC',
            'alias' => 'p',
            'attribute' => true
        ),
        array(
            'name' => 'Ecotax',
            'field' => 'ecotax',
            'database' => 'products',
            'import' => 19,
            'import_combination' => 9,
            'import_combination_name' => 'EcoTax',
            'import_name' => 'Ecotax',
            'alias' => 'p',
            'attribute' => true
        ),
        array(
            'name' => 'Unity',
            'field' => 'unity',
            'database' => 'products',
            'import' => 28,
            'import_name' => 'Unity',
            'alias' => 'p'
        ),
        array(
            'name' => 'Unit Price Ratio',
            'field' => 'unit_price_ratio',
            'database' => 'products',
            'import' => 29,
            'import_name' => 'Unit Price',
            'alias' => 'p'
        ),
        array(
            'name' => 'Minimal Quantity',
            'field' => 'minimal_quantity',
            'database' => 'products',
            'import' => 25,
            'import_combination' => 11,
            'import_combination_name' => 'Minimal quantity',
            'import_name' => 'Minimal quantity',
            'alias' => 'p',
            'attribute' => true
        ),
        array(
            'name' => 'Additional Shipping Cost',
            'field' => 'additional_shipping_cost',
            'database' => 'products',
            'import' => 27,
            'import_name' => 'Additional shipping cost',
            'alias' => 'p'
        ),
        array(
            'name' => 'Location',
            'field' => 'location',
            'database' => 'products',
            'alias' => 'p',
            'attribute' => true
        ),
        array(
            'name' => 'Width',
            'field' => 'width',
            'database' => 'products',
            'import' => 20,
            'import_name' => 'Width',
            'alias' => 'p'
        ),
        array(
            'name' => 'Height',
            'field' => 'height',
            'database' => 'products',
            'import' => 21,
            'import_name' => 'Height',
            'alias' => 'p'
        ),
        array(
            'name' => 'Depth',
            'field' => 'depth',
            'database' => 'products',
            'import' => 22,
            'import_name' => 'Depth',
            'alias' => 'p'
        ),
        array(
            'name' => 'Weight',
            'field' => 'weight',
            'database' => 'products',
            'import' => 23,
            'import_combination' => 10,
            'import_combination_name' => 'Impact on weight',
            'import_name' => 'Weight',
            'alias' => 'p',
            'attribute' => true
        ),
        array(
            'name' => 'Out Of Stock',
            'field' => 'out_of_stock',
            'database' => 'products',
            'import' => 53,
            'import_name' => 'Out of stock',
            'alias' => 'p'
        ),
        array('name' => 'Quantity Discount', 'field' => 'quantity_discount', 'database' => 'products', 'alias' => 'p'),
        array(
            'name' => 'Customizable',
            'field' => 'customizable',
            'database' => 'products',
            'import' => 49,
            'import_name' => 'Customizable (0 = No, 1 = Yes)',
            'alias' => 'p'
        ),
        array(
            'name' => 'Uploadable Files',
            'field' => 'uploadable_files',
            'database' => 'products',
            'import' => 50,
            'import_name' => 'Uploadable files (0 = No, 1 = Yes)',
            'alias' => 'p'
        ),
        array(
            'name' => 'Text Fields',
            'field' => 'text_fields',
            'database' => 'products',
            'import' => 52,
            'import_name' => 'Text fields (0 = No, 1 = Yes)',
            'alias' => 'p'
        ),
        array(
            'name' => 'Available For Order',
            'field' => 'available_for_order',
            'database' => 'products',
            'import' => 38,
            'import_name' => 'Available for order (0 = No, 1 = Yes)',
            'alias' => 'p'
        ),
        array(
            'name' => 'Condition',
            'field' => 'condition',
            'database' => 'products',
            'import' => 48,
            'import_name' => 'Condition',
            'alias' => 'p'
        ),
        array(
            'name' => 'Show Price',
            'field' => 'show_price',
            'database' => 'products',
            'import' => 41,
            'import_name' => 'Show Price',
            'alias' => 'p'
        ),
        array('name' => 'Indexed', 'field' => 'indexed', 'database' => 'products', 'alias' => 'p'),
        array('name' => 'Cache Is Pack', 'field' => 'cache_is_pack', 'database' => 'products', 'alias' => 'p'),
        array(
            'name' => 'Cache Has Attachments',
            'field' => 'cache_has_attachments',
            'database' => 'products',
            'alias' => 'p'
        ),
        array(
            'name' => 'Cache Default Attribute',
            'field' => 'cache_default_attribute',
            'database' => 'products',
            'alias' => 'p'
        ),
        array(
            'name' => 'Link Rewrite',
            'field' => 'link_rewrite',
            'database' => 'products_lang',
            'import' => 36,
            'import_name' => 'URL rewritten',
            'alias' => 'pl'
        ),
        array('name' => 'Url Product', 'field' => 'url_product', 'database' => 'other'),
        array(
            'name' => 'Features',
            'field' => 'features',
            'database' => 'other',
            'import' => 46,
            'import_name' => 'Feature(Name:Value:Position)'
        ),
        array('name' => 'Attributes', 'field' => 'attributes', 'database' => 'other', 'attribute' => true),
        array(
            'name' => 'Attributes Name',
            'field' => 'attributes_name',
            'database' => 'other',
            'attribute' => true,
            'import_combination' => 2,
            'import_combination_name' => 'Attributes Name'
        ),
        array(
            'name' => 'Attributes Value',
            'field' => 'attributes_value',
            'database' => 'other',
            'attribute' => true,
            'import_combination' => 3,
            'import_combination_name' => 'Attributes Value'
        ),
        array(
            'name' => 'Visibility',
            'field' => 'visibility',
            'database' => 'products',
            'import' => 26,
            'import_name' => 'Visibility',
            'alias' => 'p'
        ),
        array(
            'name' => 'Product available date',
            'field' => 'available_date',
            'database' => 'products',
            'import' => 39,
            'import_name' => 'Product available date',
            'alias' => 'p'
        ),
        array(
            'name' => 'Discount amount',
            'field' => 'discount_amount',
            'database' => 'specific_price',
            'import' => 9,
            'import_name' => 'Discount amount',
            'alias' => 'sp_tmp'
        ),
        array(
            'name' => 'Discount percent',
            'field' => 'discount_percent',
            'database' => 'specific_price',
            'import' => 10,
            'import_name' => 'Discount percent',
            'alias' => 'sp_tmp'
        ),
        array(
            'name' => 'Discount from (yyyy-mm-dd)',
            'field' => 'from',
            'database' => 'specific_price',
            'import' => 11,
            'import_name' => 'Discount from (yyyy-mm-dd)',
            'alias' => 'sp_tmp'
        ),
        array(
            'name' => 'Discount to (yyyy-mm-dd)',
            'field' => 'to',
            'database' => 'specific_price',
            'import' => 12,
            'import_name' => 'Discount to (yyyy-mm-dd)',
            'alias' => 'sp_tmp'
        ),
        array(
            'name' => 'Cover',
            'field' => 'image',
            'database' => 'other',
            'import' => 42,
            'import_name' => 'Image URLs (x,y,z...)'
        ),
        array(
            'name' => 'Id shop default',
            'field' => 'id_shop_default',
            'database' => 'products',
            'import' => 54,
            'import_name' => 'ID / Name of shop',
            'alias' => 'p',
            'import_combination' => 2,
            'import_combination_name' => 'ID / Name of shop'
        ),
        array(
            'name' => 'Advanced stock management',
            'field' => 'advanced_stock_management',
            'database' => 'products',
            'import' => 55,
            'import_name' => 'Advanced stock managment',
            'import_combination' => 20,
            'import_combination_name' => 'Advanced stock managment',
            'alias' => 'p'
        ),
        array(
            'name' => 'Depends On Stock',
            'field' => 'depends_on_stock',
            'database' => 'other',
            'import' => 56,
            'import_name' => 'Depends On Stock',
            'import_combination' => 21,
            'import_combination_name' => 'Depends on stock'
        ),
        array(
            'name' => 'Warehouse',
            'field' => 'warehouse',
            'database' => 'other',
            'import' => 57,
            'import_name' => 'Warehouse',
            'import_combination' => 22,
            'import_combination_name' => 'Warehouse'
        ),
        array(
            'name' => 'Image alt',
            'field' => 'image_alt',
            'database' => 'other',
            'import' => 17,
            'import_name' => 'Image alt',
            'import_combination' => 17,
            'import_combination_name' => 'Image alt texts (x,y,z...)',
            'attribute' => true
        ),
        array(
            'name' => 'Image position',
            'field' => 'image_position',
            'database' => 'other',
            'import' => 15,
            'import_name' => 'Image position',
            'import_combination' => 16,
            'import_combination_name' => 'Image position',
            'attribute' => true
        ),
        array(
            'name' => 'Default (0 = No 1 = Yes)',
            'field' => 'default_combination',
            'database' => 'other',
            'import_combination' => 16,
            'import_combination_name' => 'Default (0 = No, 1 = Yes)',
            'attribute' => true
        ),
    );
    public $orders = array(
        //PS_ORDER
        array('name' => 'Order No', 'field' => 'id_order', 'database' => 'orders', 'alias' => 'o'),
        array(
            'name' => 'Reference',
            'field' => 'reference',
            'database' => 'orders',
            'alias' => 'o',
            'attribute' => true
        ),
        array('name' => 'Code (voucher)', 'field' => 'code', 'database' => 'other'),
        //SHOP
        array('name' => 'Payment module', 'field' => 'module', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Payment', 'field' => 'payment', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Total paid', 'field' => 'total_paid', 'database' => 'orders', 'alias' => 'o'),
        array(
            'name' => 'Total paid tax incl',
            'field' => 'total_paid_tax_incl',
            'database' => 'orders',
            'alias' => 'o'
        ),
        array(
            'name' => 'Total paid tax excl',
            'field' => 'total_paid_tax_excl',
            'database' => 'orders',
            'alias' => 'o'
        ),
        array(
            'name' => 'Total products with tax',
            'field' => 'total_products_wt',
            'database' => 'orders',
            'alias' => 'o'
        ),
        array('name' => 'Total paid real', 'field' => 'total_paid_real', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Total products', 'field' => 'total_products', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Total shipping', 'field' => 'total_shipping', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Total wrapping', 'field' => 'total_wrapping', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Shipping number', 'field' => 'shipping_number', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Delivery number', 'field' => 'delivery_number', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Invoice number', 'field' => 'invoice_number', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Invoice date', 'field' => 'invoice_date', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Delivery date', 'field' => 'delivery_date', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Date added', 'field' => 'date_add', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Date updated', 'field' => 'date_upd', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Total discounts', 'field' => 'total_discounts', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Gift message', 'field' => 'gift_message', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Valid', 'field' => 'valid', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Carrier id', 'field' => 'id_carrier', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Customer id', 'field' => 'id_customer', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Recycled packaging', 'field' => 'recyclable', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Gift wrapping', 'field' => 'gift', 'database' => 'orders', 'alias' => 'o'),
        array('name' => 'Customization', 'field' => 'customization', 'database' => 'other', 'alias' => 'o'),
        //PS_CUSTOMER
        array('name' => 'Customer Firstname', 'field' => 'firstname', 'database' => 'customer', 'alias' => 'cu'),
        array('name' => 'Customer Lastname', 'field' => 'lastname', 'database' => 'customer', 'alias' => 'cu'),
        array('name' => 'Customer Email', 'field' => 'email', 'database' => 'customer', 'alias' => 'cu'),
        array('name' => 'Customer id language', 'field' => 'id_lang', 'database' => 'customer', 'alias' => 'cu'),
        //PS_ADRESS
        array(
            'name' => 'Delivery Gender',
            'field' => 'delivery_name',
            'as' => true,
            'database' => 'gender',
            'alias' => 'gl'
        ),
        array('name' => 'Delivery Company Name', 'field' => 'company', 'database' => 'address', 'alias' => 'a'),
        array(
            'name' => 'Delivery Firstname',
            'field' => 'delivery_firstname',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery Lastname',
            'field' => 'delivery_lastname',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery address line 1',
            'field' => 'delivery_address1',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery address line 2',
            'field' => 'delivery_address2',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery postcode',
            'field' => 'delivery_postcode',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery city',
            'field' => 'delivery_city',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery phone',
            'field' => 'delivery_phone',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery phone(mobile)',
            'field' => 'delivery_phone_mobile',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery VAT',
            'field' => 'delivery_vat_number',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        array(
            'name' => 'Delivery DNI',
            'field' => 'delivery_dni',
            'as' => true,
            'database' => 'address',
            'alias' => 'a'
        ),
        //PS_STATE
        array('name' => 'Delivery country iso code', 'field' => 'iso_code', 'database' => 'country', 'alias' => 'co'),
        array('name' => 'Delivery state', 'field' => 'state_name', 'as' => true, 'database' => 'state', 'alias' => 's'),
        //PS_COUNTRY_LANG
        array(
            'name' => 'Delivery country',
            'field' => 'country_name',
            'as' => true,
            'database' => 'country_lang',
            'alias' => 'cl'
        ),
        //PS_ADRESS
        array(
            'name' => 'Invoice address line 1',
            'field' => 'invoice_address1',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice address line 2',
            'field' => 'invoice_address2',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice postcode',
            'field' => 'invoice_postcode',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice city',
            'field' => 'invoice_city',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice phone',
            'field' => 'invoice_phone',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice phone (mobile)',
            'field' => 'invoice_phone_mobile',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice gender',
            'field' => 'invoice_name',
            'as' => true,
            'database' => 'gender',
            'alias' => 'inv_gl'
        ),
        array(
            'name' => 'Invoice firstname',
            'field' => 'invoice_firstname',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice lastname',
            'field' => 'invoice_lastname',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        array(
            'name' => 'Invoice company name',
            'field' => 'invoice_company',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a'
        ),
        //ORDER_PAYMENT
        array('name' => 'Transaction Id', 'field' => 'transaction_id', 'database' => 'order_payment', 'alias' => 'op'),
        //PS_CARRIER
        array(
            'name' => 'Name carrier',
            'field' => 'carrier_name',
            'as' => true,
            'database' => 'carrier',
            'alias' => 'ca'
        ),
        //PS_ORDER_DETAIL
        array('name' => 'Product ID', 'field' => 'product_id', 'database' => 'order_detail', 'alias' => 'od'),
        array('name' => 'Product Ref', 'field' => 'product_reference', 'database' => 'order_detail', 'alias' => 'od'),
        array('name' => 'Product Name', 'field' => 'product_name', 'database' => 'order_detail', 'alias' => 'od'),
        array('name' => 'Product Price', 'field' => 'product_price', 'database' => 'order_detail', 'alias' => 'od'),
        array(
            'name' => 'Product Quantity',
            'field' => 'product_quantity',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array('name' => 'Shop name', 'field' => 'shop_name', 'database' => 'shop', 'as' => true, 'alias' => 'sh'),

        array('name' => 'Message', 'field' => 'message', 'database' => 'message', 'alias' => 'm'),
        array(
            'name' => 'Order currency',
            'field' => 'currency_iso_code',
            'database' => 'currency',
            'as' => true,
            'alias' => 'cur'
        ),
        array(
            'name' => 'Product quantity discount',
            'field' => 'product_quantity_discount',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Reduction amount',
            'field' => 'reduction_amount',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Reduction amount tax incl',
            'field' => 'reduction_amount_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Reduction amount tax excl',
            'field' => 'reduction_amount_tax_excl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product group reduction',
            'field' => 'group_reduction',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array('name' => 'Product ean13', 'field' => 'product_ean13', 'database' => 'order_detail', 'alias' => 'od'),
        array(
            'name' => 'Product Unit price tax incl',
            'field' => 'unit_price_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Unit price tax excl',
            'field' => 'unit_price_tax_excl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Total price tax excl',
            'field' => 'total_price_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Total price tax excl',
            'field' => 'total_price_tax_excl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product Total shipping price tax excl',
            'field' => 'total_shipping_price_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array('name' => 'Product ecotax', 'field' => 'ecotax', 'database' => 'order_detail', 'alias' => 'od'),
        array(
            'name' => 'Product ecotax rate',
            'field' => 'ecotax_tax_rate',
            'database' => 'order_detail',
            'alias' => 'od'
        ),
        array(
            'name' => 'Product tax rate (order detail table)',
            'field' => 'tax_rate',
            'database' => 'tax',
            'alias' => 'od'
        ),
        array('name' => 'Product tax rate (tax table)', 'field' => 'rate', 'database' => 'tax', 'alias' => 't'),
        array(
            'name' => 'Product tax unit amount',
            'field' => 'unit_amount',
            'database' => 'order_detail_tax',
            'alias' => 'odt'
        ),
        array(
            'name' => 'Product tax total amount',
            'field' => 'total_amount',
            'database' => 'order_detail_tax',
            'alias' => 'odt'
        ),
        array(
            'name' => 'Order state',
            'field' => 'orderstate_name',
            'database' => 'order_state_lang',
            'as' => true,
            'alias' => 'osl'
        ),
        array('name' => 'Employee name (last state)', 'field' => 'employee_name', 'database' => 'other'),
        array(
            'name' => 'Delivery state iso',
            'field' => 'state_iso_code',
            'as' => true,
            'database' => 'state',
            'alias' => 's'
        ),
    );
    public $categories = array(
        array(
            'name' => 'Id category',
            'field' => 'id_category',
            'database' => 'category',
            'alias' => 'c',
            'import' => 1,
            'import_name' => 'ID'
        ),
        array(
            'name' => 'Id parent',
            'field' => 'id_parent',
            'database' => 'category',
            'alias' => 'c',
            'import' => 4,
            'import_name' => 'Parent category'
        ),
        array('name' => 'Id shop default', 'field' => 'id_shop_default', 'database' => 'category', 'alias' => 'c'),
        array('name' => 'Level depth', 'field' => 'level_depth', 'database' => 'category', 'alias' => 'c'),
        array('name' => 'nleft', 'field' => 'nleft', 'database' => 'category', 'alias' => 'c'),
        array('name' => 'nright', 'field' => 'nright', 'database' => 'category', 'alias' => 'c'),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'category',
            'alias' => 'c',
            'import' => 2,
            'import_name' => 'Active (0/1)'
        ),
        array(
            'name' => 'Is root category',
            'field' => 'is_root_category',
            'database' => 'category',
            'alias' => 'c',
            'import' => 5,
            'import_name' => 'Root category (0/1)'
        ),
        array('name' => 'Id group', 'field' => 'id_group', 'database' => 'other'),
        array('name' => 'Id shop', 'field' => 'id_shop', 'database' => 'category_lang', 'alias' => 'cl'),
        array(
            'name' => 'Name',
            'field' => 'name',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 3,
            'import_name' => 'Name *'
        ),
        array(
            'name' => 'Description',
            'field' => 'description',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 6,
            'import_name' => 'Description'
        ),
        array(
            'name' => 'Link rewrite',
            'field' => 'link_rewrite',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 10,
            'import_name' => 'URL rewritten'
        ),
        array(
            'name' => 'Meta title',
            'field' => 'meta_title',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 7,
            'import_name' => 'Meta title'
        ),
        array(
            'name' => 'Meta keywords',
            'field' => 'meta_keywords',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 8,
            'import_name' => 'Meta keywords'
        ),
        array(
            'name' => 'Meta description',
            'field' => 'meta_description',
            'database' => 'category_lang',
            'alias' => 'cl',
            'import' => 9,
            'import_name' => 'Meta description'
        ),
        array('name' => 'Position', 'field' => 'position', 'database' => 'category_shop', 'alias' => 'category_shop'),
        array(
            'name' => 'Image URL',
            'field' => 'image',
            'database' => 'other',
            'import' => 11,
            'import_name' => 'Image URL'
        ),
    );
    public $manufacturers = array(
        array(
            'name' => 'id manufacturer',
            'field' => 'id_manufacturer',
            'database' => 'manufacturer',
            'alias' => 'm',
            'import' => 1,
            'import_name' => 'ID'
        ),
        array(
            'name' => 'name',
            'field' => 'name',
            'database' => 'manufacturer',
            'alias' => 'm',
            'import' => 3,
            'import_name' => 'Name *'
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'manufacturer',
            'alias' => 'm',
            'import' => 2,
            'import_name' => 'Active (0/1)'
        ),
        array(
            'name' => 'description',
            'field' => 'description',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 4,
            'import_name' => 'Description'
        ),
        array(
            'name' => 'short description',
            'field' => 'short_description',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 5,
            'import_name' => 'Short description'
        ),
        array(
            'name' => 'meta title',
            'field' => 'meta_title',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 6,
            'import_name' => 'Meta title'
        ),
        array(
            'name' => 'meta keywords',
            'field' => 'meta_keywords',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 7,
            'import_name' => 'Meta keywords'
        ),
        array(
            'name' => 'meta description',
            'field' => 'meta_description',
            'database' => 'manufacturer',
            'alias' => 'ml',
            'import' => 8,
            'import_name' => 'Meta description'
        ),
        array('name' => 'id shop', 'field' => 'id_shop', 'database' => 'manufacturer', 'alias' => 'manufacturer_shop'),
        array(
            'name' => 'Image URL',
            'field' => 'image',
            'database' => 'other',
            'import' => 9,
            'import_name' => 'Image URL'
        ),
    );
    public $suppliers = array(
        array(
            'name' => 'id supplier',
            'field' => 'id_supplier',
            'database' => 'supplier',
            'alias' => 's',
            'import' => 1,
            'import_name' => 'ID'
        ),
        array(
            'name' => 'name',
            'field' => 'name',
            'database' => 'supplier',
            'alias' => 's',
            'import' => 3,
            'import_name' => 'Name *'
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'supplier',
            'alias' => 's',
            'import' => 2,
            'import_name' => 'Active (0/1)'
        ),
        array(
            'name' => 'description',
            'field' => 'description',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 4,
            'import_name' => 'Description'
        ),
        array(
            'name' => 'meta title',
            'field' => 'meta_title',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 6,
            'import_name' => 'Meta title'
        ),
        array(
            'name' => 'meta keywords',
            'field' => 'meta_keywords',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 7,
            'import_name' => 'Meta keywords'
        ),
        array(
            'name' => 'meta description',
            'field' => 'meta_description',
            'database' => 'supplier',
            'alias' => 'sl',
            'import' => 8,
            'import_name' => 'Meta description'
        ),
        array('name' => 'id shop', 'field' => 'id_shop', 'database' => 'supplier', 'alias' => 'supplier_shop'),
        array(
            'name' => 'Image URL',
            'field' => 'image',
            'database' => 'other',
            'import' => 9,
            'import_name' => 'Image URL'
        ),
    );
    public $customers = array(
        array(
            'name' => 'id customer',
            'field' => 'id_customer',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 1,
            'import_name' => 'ID'
        ),
        array(
            'name' => 'id gender',
            'field' => 'id_gender',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 3,
            'import_name' => 'Titles ID (Mr = 1, Ms = 2, else 0)'
        ),
        array('name' => 'company', 'field' => 'company', 'database' => 'customer', 'alias' => 'c'),
        array('name' => 'siret', 'field' => 'siret', 'database' => 'customer', 'alias' => 'c'),
        array('name' => 'ape', 'field' => 'ape', 'database' => 'customer', 'alias' => 'c'),
        array(
            'name' => 'firstname',
            'field' => 'firstname',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 8,
            'import_name' => 'First Name *'
        ),
        array(
            'name' => 'lastname',
            'field' => 'lastname',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 7,
            'import_name' => 'Last Name *'
        ),
        array(
            'name' => 'email',
            'field' => 'email',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 4,
            'import_name' => 'Email *'
        ),
        array(
            'name' => 'birthday',
            'field' => 'birthday',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 6,
            'import_name' => 'Birthday (yyyy-mm-dd)'
        ),
        array(
            'name' => 'newsletter',
            'field' => 'newsletter',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 9,
            'import_name' => 'Newsletter (0/1)'
        ),
        array('name' => 'website', 'field' => 'website', 'database' => 'customer', 'alias' => 'c'),
        array(
            'name' => 'password',
            'field' => 'passwd',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 5,
            'import_name' => 'Passowrd *'
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 2,
            'import_name' => 'Active (0/1)'
        ),
        array(
            'name' => 'optin',
            'field' => 'optin',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 10,
            'import_name' => 'Opt-in (0/1)'
        ),
        array(
            'name' => 'date add',
            'field' => 'date_add',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 11,
            'import_name' => 'Registration date (yyyy-mm-dd)'
        ),
        array(
            'name' => 'default group id',
            'field' => 'id_default_group',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 12,
            'import_name' => 'Default group ID'
        ),
        array(
            'name' => 'groups',
            'field' => 'groups',
            'database' => 'other',
            'import' => 13,
            'import_name' => 'Groups (x,y,z...)'
        ),

        array(
            'name' => 'address company',
            'field' => 'address_company',
            'database' => 'address',
            'as' => true,
            'alias' => 'a'
        ),
        array(
            'name' => 'address firstname',
            'field' => 'address_firstname',
            'database' => 'address',
            'as' => true,
            'alias' => 'a'
        ),
        array(
            'name' => 'address lastname',
            'field' => 'address_lastname',
            'database' => 'address',
            'as' => true,
            'alias' => 'a'
        ),
        array('name' => 'address address1', 'field' => 'address1', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address address2', 'field' => 'address2', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address postcode', 'field' => 'postcode', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address city', 'field' => 'city', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address other', 'field' => 'other', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address phone', 'field' => 'phone', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address phone_mobile', 'field' => 'phone_mobile', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address vat_number', 'field' => 'vat_number', 'database' => 'address', 'alias' => 'a'),
        array('name' => 'address dni', 'field' => 'dni', 'database' => 'address', 'alias' => 'a'),
        array(
            'name' => 'address active',
            'field' => 'address_active',
            'database' => 'address',
            'alias' => 'a',
            'as' => true
        ),
        array('name' => 'address state', 'field' => 'name', 'database' => 'state', 'alias' => 's'),
        array(
            'name' => 'address country',
            'field' => 'country_name',
            'database' => 'country_lang',
            'alias' => 'co',
            'as' => true
        ),
    );
    public $newsletters = array(
        array('name' => 'Email', 'field' => 'email', 'database' => 'newsletter'),
        array('name' => 'Date add', 'field' => 'newsletter_date_add', 'database' => 'newsletter'),
        array('name' => 'Ip', 'field' => 'ip_registration_newsletter', 'database' => 'newsletter'),
        array('name' => 'Referer', 'field' => 'http_referer', 'database' => 'newsletter'),
        array('name' => 'Active', 'field' => 'active', 'database' => 'newsletter'),
    );
    public $addresses = array(
        array(
            'name' => 'id',
            'field' => 'id_address',
            'database' => 'address',
            'alias' => 'a',
            'import' => 1,
            'import_name' => 'id'
        ),
        array(
            'name' => 'alias',
            'field' => 'alias',
            'database' => 'address',
            'alias' => 'a',
            'import' => 2,
            'import_name' => 'Alias*'
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'address',
            'alias' => 'a',
            'import' => 3,
            'import_name' => 'Active (0/1)'
        ),
        array(
            'name' => 'email',
            'field' => 'email',
            'database' => 'address',
            'alias' => 'cu',
            'import' => 4,
            'import_name' => 'Customer e-mail*'
        ),
        array(
            'name' => 'id customer',
            'field' => 'id_customer',
            'database' => 'address',
            'alias' => 'a',
            'import' => 5,
            'import_name' => 'Customer ID'
        ),
        array(
            'name' => 'manufacturer',
            'field' => 'manufacturer_name',
            'database' => 'manufacturer',
            'alias' => 'm',
            'as' => true,
            'import' => 6,
            'import_name' => 'Manufacturer'
        ),
        array(
            'name' => 'supplier',
            'field' => 'supplier_name',
            'database' => 'supplier',
            'alias' => 's',
            'as' => true,
            'import' => 7,
            'import_name' => 'Supplier'
        ),
        array(
            'name' => 'company',
            'field' => 'company',
            'database' => 'address',
            'alias' => 'a',
            'import' => 8,
            'import_name' => 'Company'
        ),
        array(
            'name' => 'lastname',
            'field' => 'lastname',
            'database' => 'address',
            'alias' => 'a',
            'import' => 9,
            'import_name' => 'Lastname*'
        ),
        array(
            'name' => 'firstname',
            'field' => 'firstname',
            'database' => 'address',
            'alias' => 'a',
            'import' => 10,
            'import_name' => 'Firstname*'
        ),
        array(
            'name' => 'address 1',
            'field' => 'address1',
            'database' => 'address',
            'alias' => 'a',
            'import' => 11,
            'import_name' => 'Address 1*'
        ),
        array(
            'name' => 'address 2',
            'field' => 'address2',
            'database' => 'address',
            'alias' => 'a',
            'import' => 12,
            'import_name' => 'Address 2*'
        ),
        array(
            'name' => 'postcode',
            'field' => 'postcode',
            'database' => 'address',
            'alias' => 'a',
            'import' => 13,
            'import_name' => 'Zipcode*'
        ),
        array(
            'name' => 'city',
            'field' => 'city',
            'database' => 'address',
            'alias' => 'a',
            'import' => 14,
            'import_name' => 'City*'
        ),
        array(
            'name' => 'country',
            'field' => 'country_name',
            'database' => 'country_lang',
            'alias' => 'cl',
            'as' => true,
            'import' => 15,
            'import_name' => 'Country*'
        ),
        array(
            'name' => 'state',
            'field' => 'state_name',
            'database' => 'state',
            'alias' => 'st',
            'as' => true,
            'import' => 16,
            'import_name' => 'State*'
        ),
        array(
            'name' => 'other',
            'field' => 'other',
            'database' => 'address',
            'alias' => 'a',
            'import' => 17,
            'import_name' => 'Other'
        ),
        array(
            'name' => 'phone',
            'field' => 'phone',
            'database' => 'address',
            'alias' => 'a',
            'import' => 18,
            'import_name' => 'Phone'
        ),
        array(
            'name' => 'mobile',
            'field' => 'phone_mobile',
            'database' => 'address',
            'alias' => 'a',
            'import' => 19,
            'import_name' => 'Mobile Phone'
        ),
        array(
            'name' => 'vat number',
            'field' => 'vat_number',
            'database' => 'address',
            'alias' => 'a',
            'import' => 20,
            'import_name' => 'VAT number'
        ),
        array(
            'name' => 'dni',
            'field' => 'dni',
            'database' => 'address',
            'alias' => 'a',
            'import' => 21,
            'import_name' => 'DNI'
        ),
    );
    public $export_types = array(
        'products',
        'orders',
        'categories',
        'manufacturers',
        'newsletters',
        'suppliers',
        'customers',
        'addresses',
    );
}
