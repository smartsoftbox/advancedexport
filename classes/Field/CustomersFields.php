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

class CustomersFields extends BaseFields
{
    public $fields = array(
        array(
            'name' => 'id customer',
            'field' => 'id_customer',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 1,
            'import_name' => 'ID',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'id gender',
            'field' => 'id_gender',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 3,
            'import_name' => 'Titles ID (Mr = 1 Ms = 2 else 0)',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'company',
            'field' => 'company',
            'database' => 'customer',
            'alias' => 'c',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'siret',
            'field' => 'siret',
            'database' => 'customer',
            'alias' => 'c',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'ape',
            'field' => 'ape',
            'database' => 'customer',
            'alias' => 'c',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'firstname',
            'field' => 'firstname',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 8,
            'import_name' => 'First Name *',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'lastname',
            'field' => 'lastname',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 7,
            'import_name' => 'Last Name *',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'email',
            'field' => 'email',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 4,
            'import_name' => 'Email *',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'birthday',
            'field' => 'birthday',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 6,
            'import_name' => 'Birthday (yyyy-mm-dd)',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'newsletter',
            'field' => 'newsletter',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 9,
            'import_name' => 'Newsletter (0/1)',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'website',
            'field' => 'website',
            'database' => 'customer',
            'alias' => 'c',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'password',
            'field' => 'passwd',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 5,
            'import_name' => 'Passowrd *',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'active',
            'field' => 'active',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 2,
            'import_name' => 'Active (0/1)',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'optin',
            'field' => 'optin',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 10,
            'import_name' => 'Opt-in (0/1)',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'date add',
            'field' => 'date_add',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 11,
            'import_name' => 'Registration date (yyyy-mm-dd)',
            'group15' => CustomerGroup::INFORMATION
        ),
        array(
            'name' => 'default group id',
            'field' => 'id_default_group',
            'database' => 'customer',
            'alias' => 'c',
            'import' => 12,
            'import_name' => 'Default group ID',
            'group15' => CustomerGroup::ASSOCIATION
        ),
        array(
            'name' => 'groups',
            'field' => 'groups',
            'database' => 'other',
            'import' => 13,
            'import_name' => 'Groups (x y z...)',
            'group15' => CustomerGroup::ASSOCIATION
        ),
        array(
            'name' => 'address company',
            'field' => 'address_company',
            'database' => 'address',
            'as' => true,
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address firstname',
            'field' => 'address_firstname',
            'database' => 'address',
            'as' => true,
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address lastname',
            'field' => 'address_lastname',
            'database' => 'address',
            'as' => true,
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address address1',
            'field' => 'address1',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address address2',
            'field' => 'address2',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address postcode',
            'field' => 'postcode',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address city',
            'field' => 'city',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address other',
            'field' => 'other',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address phone',
            'field' => 'phone',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address phone mobile',
            'field' => 'phone_mobile',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address vat number',
            'field' => 'vat_number',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address dni',
            'field' => 'dni',
            'database' => 'address',
            'alias' => 'a',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address active',
            'field' => 'address_active',
            'database' => 'address',
            'alias' => 'a',
            'as' => true,
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address state',
            'field' => 'name',
            'database' => 'state',
            'alias' => 's',
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'address country',
            'field' => 'country_name',
            'database' => 'country_lang',
            'alias' => 'co',
            'as' => true,
            'group15' => CustomerGroup::ADDRESS
        ),
        array(
            'name' => 'id language',
            'field' => 'id_lang',
            'database' => 'customer',
            'alias' => 'c',
            'group15' => CustomerGroup::INFORMATION
        ),
    );
}
