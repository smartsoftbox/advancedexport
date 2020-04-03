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

class AddressesFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'id',
            'field' => 'id_address',
            'database' => 'address',
            'alias' => 'a',
            'import' => 1,
            'import_name' => 'id',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'alias',
            'field' => 'alias',
            'database' => 'address',
            'alias' => 'a',
            'import' => 2,
            'import_name' => 'Alias*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'address',
            'alias' => 'a',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'email',
            'field' => 'email',
            'database' => 'address',
            'alias' => 'cu',
            'import' => 4,
            'import_name' => 'Customer e-mail*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'id customer',
            'field' => 'id_customer',
            'database' => 'address',
            'alias' => 'a',
            'import' => 5,
            'import_name' => 'Customer ID',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'manufacturer',
            'field' => 'manufacturer_name',
            'database' => 'manufacturer',
            'alias' => 'm',
            'as' => true,
            'import' => 6,
            'import_name' => 'Manufacturer',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'supplier',
            'field' => 'supplier_name',
            'database' => 'supplier',
            'alias' => 's',
            'as' => true,
            'import' => 7,
            'import_name' => 'Supplier',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'company',
            'field' => 'company',
            'database' => 'address',
            'alias' => 'a',
            'import' => 8,
            'import_name' => 'Company',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'lastname',
            'field' => 'lastname',
            'database' => 'address',
            'alias' => 'a',
            'import' => 9,
            'import_name' => 'Lastname*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'firstname',
            'field' => 'firstname',
            'database' => 'address',
            'alias' => 'a',
            'import' => 10,
            'import_name' => 'Firstname*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'address 1',
            'field' => 'address1',
            'database' => 'address',
            'alias' => 'a',
            'import' => 11,
            'import_name' => 'Address 1*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'address 2',
            'field' => 'address2',
            'database' => 'address',
            'alias' => 'a',
            'import' => 12,
            'import_name' => 'Address 2*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'postcode',
            'field' => 'postcode',
            'database' => 'address',
            'alias' => 'a',
            'import' => 13,
            'import_name' => 'Zipcode*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'city',
            'field' => 'city',
            'database' => 'address',
            'alias' => 'a',
            'import' => 14,
            'import_name' => 'City*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'country',
            'field' => 'country_name',
            'database' => 'country_lang',
            'alias' => 'cl',
            'as' => true,
            'import' => 15,
            'import_name' => 'Country*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'state',
            'field' => 'state_name',
            'database' => 'state',
            'alias' => 'st',
            'as' => true,
            'import' => 16,
            'import_name' => 'State*',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'other',
            'field' => 'other',
            'database' => 'address',
            'alias' => 'a',
            'import' => 17,
            'import_name' => 'Other',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'phone',
            'field' => 'phone',
            'database' => 'address',
            'alias' => 'a',
            'import' => 18,
            'import_name' => 'Phone',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'mobile',
            'field' => 'phone_mobile',
            'database' => 'address',
            'alias' => 'a',
            'import' => 19,
            'import_name' => 'Mobile Phone',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'vat number',
            'field' => 'vat_number',
            'database' => 'address',
            'alias' => 'a',
            'import' => 20,
            'import_name' => 'VAT number',
            'group15' => AddressGroup::INFORMATION
        ),
        array(
            'name' => 'dni',
            'field' => 'dni',
            'database' => 'address',
            'alias' => 'a',
            'import' => 21,
            'import_name' => 'DNI',
            'group15' => AddressGroup::INFORMATION
        )
    );
}
