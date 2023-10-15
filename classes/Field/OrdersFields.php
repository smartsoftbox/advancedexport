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

class OrdersFields extends BaseFields
{
    public $fields = array(
        //PS_ORDER
        array(
            'name' => 'Order No',
            'field' => 'id_order',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Reference',
            'field' => 'reference',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Code (voucher)',
            'field' => 'code',
            'database' => 'other',
            'group15' => OrderGroup::ORDER
        ),
        //SHOP
        array(
            'name' => 'Payment module',
            'field' => 'module',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Payment',
            'field' => 'payment',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::PAYMENT
        ),
        array(
            'name' => 'Total paid',
            'field' => 'total_paid',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total paid tax incl',
            'field' => 'total_paid_tax_incl',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total paid tax excl',
            'field' => 'total_paid_tax_excl',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total products with tax',
            'field' => 'total_products_wt',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total paid real',
            'field' => 'total_paid_real',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total products',
            'field' => 'total_products',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total shipping',
            'field' => 'total_shipping',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total wrapping',
            'field' => 'total_wrapping',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Delivery number',
            'field' => 'delivery_number',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Invoice number',
            'field' => 'invoice_number',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Invoice date',
            'field' => 'invoice_date',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Delivery date',
            'field' => 'delivery_date',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Date added',
            'field' => 'date_add',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Date updated',
            'field' => 'date_upd',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total discounts',
            'field' => 'total_discounts',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Gift message',
            'field' => 'gift_message',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::MESSAGES
        ),
        array(
            'name' => 'Valid',
            'field' => 'valid',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Carrier id',
            'field' => 'id_carrier',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Customer id',
            'field' => 'id_customer',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::CUSTOMER
        ),
        array(
            'name' => 'Recycled packaging',
            'field' => 'recyclable',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Gift wrapping',
            'field' => 'gift',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Customization',
            'field' => 'customization',
            'database' => 'other',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        //PS_CUSTOMER
        array(
            'name' => 'Customer Firstname',
            'field' => 'firstname',
            'database' => 'customer',
            'alias' => 'cu',
            'group15' => OrderGroup::CUSTOMER
        ),
        array(
            'name' => 'Customer Lastname',
            'field' => 'lastname',
            'database' => 'customer',
            'alias' => 'cu',
            'group15' => OrderGroup::CUSTOMER
        ),
        array(
            'name' => 'Customer Full Name',
            'field' => 'fullname_firstname',
            'as' => true,
            'concat' => 'lastname',
            'database' => 'customer',
            'alias' => 'cu',
            'group15' => OrderGroup::CUSTOMER
        ),
        array(
            'name' => 'Customer Email',
            'field' => 'email',
            'database' => 'customer',
            'alias' => 'cu',
            'group15' => OrderGroup::CUSTOMER
        ),
        array(
            'name' => 'Customer id language',
            'field' => 'id_lang',
            'database' => 'customer',
            'alias' => 'cu',
            'group15' => OrderGroup::CUSTOMER
        ),
        //PS_ADRESS
        array(
            'name' => 'Delivery Gender',
            'field' => 'delivery_name',
            'as' => true,
            'database' => 'gender',
            'alias' => 'gl',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery Company Name',
            'field' => 'company',
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery Firstname',
            'field' => 'delivery_firstname',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery Lastname',
            'field' => 'delivery_lastname',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery address line 1',
            'field' => 'delivery_address1',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery address line 2',
            'field' => 'delivery_address2',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery postcode',
            'field' => 'delivery_postcode',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery city',
            'field' => 'delivery_city',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery phone',
            'field' => 'delivery_phone',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery phone(mobile)',
            'field' => 'delivery_phone_mobile',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery VAT',
            'field' => 'delivery_vat_number',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery DNI',
            'field' => 'delivery_dni',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),

        //PS_STATE
        array(
            'name' => 'Delivery country iso code',
            'field' => 'iso_code',
            'database' => 'country',
            'alias' => 'co',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Delivery state',
            'field' => 'state_name',
            'as' => true,
            'database' => 'state',
            'alias' => 's',
            'group15' => OrderGroup::DELIVERY
        ),
        //PS_COUNTRY_LANG
        array(
            'name' => 'Delivery country',
            'field' => 'country_name',
            'as' => true,
            'database' => 'country_lang',
            'alias' => 'cl',
            'group15' => OrderGroup::DELIVERY
        ),
        //PS_ADRESS
        array(
            'name' => 'Invoice address line 1',
            'field' => 'invoice_address1',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice address line 2',
            'field' => 'invoice_address2',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice postcode',
            'field' => 'invoice_postcode',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice city',
            'field' => 'invoice_city',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice phone',
            'field' => 'invoice_phone',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice phone (mobile)',
            'field' => 'invoice_phone_mobile',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice gender',
            'field' => 'invoice_name',
            'as' => true,
            'database' => 'gender',
            'alias' => 'inv_gl',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice firstname',
            'field' => 'invoice_firstname',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice lastname',
            'field' => 'invoice_lastname',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice company name',
            'field' => 'invoice_company',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        //ORDER_PAYMENT
        array(
            'name' => 'Payments',
            'field' => 'payments',
            'database' => 'other',
            'group15' => OrderGroup::PAYMENT
        ),
        //PS_CARRIER
        array(
            'name' => 'Name carrier',
            'field' => 'carrier_name',
            'as' => true,
            'database' => 'carrier',
            'alias' => 'ca',
            'group15' => OrderGroup::ORDER
        ),
        //PS_ORDER_DETAIL
        array(
            'name' => 'Product ID',
            'field' => 'product_id',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Ref',
            'field' => 'product_reference',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Name',
            'field' => 'product_name',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Price',
            'field' => 'product_price',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Quantity',
            'field' => 'product_quantity',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Shop name',
            'field' => 'shop_name',
            'database' => 'shop',
            'as' => true,
            'alias' => 'sh',
            'group15' => OrderGroup::ORDER
        ),

        array(
            'name' => 'Message',
            'field' => 'message',
            'database' => 'message',
            'alias' => 'm',
            'group15' => OrderGroup::MESSAGES
        ),
        array(
            'name' => 'Order currency',
            'field' => 'currency_iso_code',
            'database' => 'currency',
            'as' => true,
            'alias' => 'cur',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Product quantity discount',
            'field' => 'product_quantity_discount',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Reduction percent',
            'field' => 'reduction_percent',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Reduction amount',
            'field' => 'reduction_amount',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Reduction amount tax incl',
            'field' => 'reduction_amount_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Reduction amount tax excl',
            'field' => 'reduction_amount_tax_excl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product group reduction',
            'field' => 'group_reduction',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product ean13',
            'field' => 'product_ean13',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Unit price tax incl',
            'field' => 'unit_price_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Unit price tax excl',
            'field' => 'unit_price_tax_excl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Total price tax incl',
            'field' => 'total_price_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Total price tax excl',
            'field' => 'total_price_tax_excl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product Total shipping price tax excl',
            'field' => 'total_shipping_price_tax_incl',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product ecotax',
            'field' => 'ecotax',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product ecotax rate',
            'field' => 'ecotax_tax_rate',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product tax rate (order detail table)',
            'field' => 'tax_rate',
            'database' => 'tax',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product tax rate (tax table)',
            'field' => 'rate',
            'database' => 'tax',
            'alias' => 't',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product tax unit amount',
            'field' => 'unit_amount',
            'database' => 'order_detail_tax',
            'alias' => 'odt',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product tax total amount',
            'field' => 'total_amount',
            'database' => 'order_detail_tax',
            'alias' => 'odt',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Product mpn',
            'field' => 'product_mpn',
            'database' => 'order_detail',
            'alias' => 'od',
            'version' => '1.7.7',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Order state',
            'field' => 'orderstate_name',
            'database' => 'order_state_lang',
            'as' => true,
            'alias' => 'osl',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Employee name (last state)',
            'field' => 'employee_name',
            'database' => 'other',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Delivery state iso',
            'field' => 'state_iso_code',
            'as' => true,
            'database' => 'state',
            'alias' => 's',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Total product weight',
            'field' => 'total_product_weight',
            'database' => 'other',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Total shipping tax excl',
            'field' => 'total_shipping_tax_excl',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Carrier tax rate',
            'field' => 'carrier_tax_rate',
            'database' => 'orders',
            'alias' => 'o',
            'group15' => OrderGroup::ORDER
        ),
        array(
            'name' => 'Delivery Other',
            'field' => 'delivery_other',
            'as' => true,
            'database' => 'address',
            'alias' => 'a',
            'group15' => OrderGroup::DELIVERY
        ),
        array(
            'name' => 'Invoice other',
            'field' => 'invoice_other',
            'as' => true,
            'database' => 'address',
            'alias'
            => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice state',
            'field' => 'invoicestate_name',
            'as' => true,
            'database' => 'state',
            'alias' => 'inv_s',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice country',
            'field' => 'invoicecountry_name',
            'as' => true,
            'database' => 'country_lang',
            'alias' => 'inv_cl',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Orginal wholesale price',
            'field' => 'original_wholesale_price',
            'database' => 'order_detail',
            'alias' => 'od',
            'group15' => OrderGroup::PRODUCT
        ),
        array(
            'name' => 'Invoice VAT',
            'field' => 'invoice_vat_number',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice DNI',
            'field' => 'invoice_dni',
            'as' => true,
            'database' => 'address',
            'alias' => 'inv_a',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice country iso',
            'field' => 'invoicecountry_iso_code',
            'as' => true,
            'database' => 'country',
            'alias' => 'inv_co',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Invoice state iso code',
            'field' => 'invoicestate_iso_code',
            'as' => true,
            'database' => 'state',
            'alias' => 'inv_s',
            'group15' => OrderGroup::INVOICE
        ),
        array(
            'name' => 'Product isbn',
            'field' => 'product_isbn',
            'database' => 'order_detail',
            'alias' => 'od',
            'version' => 1.7,
            'group15' => OrderGroup::PRODUCT
        ),
    );
}
