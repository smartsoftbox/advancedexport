<?php

namespace LegacyTests\TestCase;

use Context;
use Export;
use AdvancedExportClass;
use Configuration;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../classes/Field/CustomFields.php';


class CustomersTest extends IntegrationTestCase
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
        $this->ae = new Export();

        $id = $this->createModelWithAllFieldsAndDefaultSettings('customers');
        $aec = new AdvancedExportClass($id);
        $this->ae->createExportFile($aec);

        $url = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/customers/test_customers.csv';
        $rows = array_map('str_getcsv', file($url));;
        foreach ($rows[0] as $key => $fieldname) {
            $this->row[$fieldname] = $rows['2'][$key];
        }
    }

    public function test_CsvFileData()
    {
        //array('name' => 'id customer', 'field' => 'id_customer', 'database' => 'customer', 'alias' => 'c', 'import' => 1, 'import_name' => 'ID'),
        $this->assertSame($this->row['id customer'], '2');
        //array('name' => 'id gender', 'field' => 'id_gender', 'database' => 'customer', 'alias' => 'c', 'import' => 3, 'import_name' => 'Titles ID (Mr = 1, Ms = 2, else 0)'),
        $this->assertSame($this->row['id gender'], '1');
        //array('name' => 'company', 'field' => 'company', 'database' => 'customer', 'alias' => 'c'),
        $this->assertSame($this->row['company'], '');
        //array('name' => 'siret', 'field' => 'siret', 'database' => 'customer', 'alias' => 'c'),
        $this->assertSame($this->row['siret'], '');
        //array('name' => 'ape', 'field' => 'ape', 'database' => 'customer', 'alias' => 'c'),
        $this->assertSame($this->row['ape'], '');
        //array('name' => 'firstname', 'field' => 'firstname', 'database' => 'customer', 'alias' => 'c', 'import' => 8, 'import_name' => 'First Name *'),
        $this->assertSame($this->row['firstname'], 'John');
        //array('name' => 'lastname', 'field' => 'lastname', 'database' => 'customer', 'alias' => 'c', 'import' => 7, 'import_name' => 'Last Name *'),
        $this->assertSame($this->row['lastname'], 'DOE');
        //array('name' => 'email', 'field' => 'email', 'database' => 'customer', 'alias' => 'c', 'import' => 4, 'import_name' => 'Email *'),
        $this->assertSame($this->row['email'], 'pub@prestashop.com');
        //array('name' => 'birthday', 'field' => 'birthday', 'database' => 'customer', 'alias' => 'c', 'import' => 6, 'import_name' => 'Birthday (yyyy-mm-dd)'),
        $this->assertSame($this->row['birthday'], '1970-01-15');
        //array('name' => 'newsletter', 'field' => 'newsletter', 'database' => 'customer', 'alias' => 'c', 'import' => 9, 'import_name' => 'Newsletter (0/1)'),
        $this->assertSame($this->row['newsletter'], '1');
        //array('name' => 'website', 'field' => 'website', 'database' => 'customer', 'alias' => 'c'),
        $this->assertSame($this->row['website'], '');
        //array('name' => 'password', 'field' => 'passwd', 'database' => 'customer', 'alias' => 'c', 'import' => 5, 'import_name' => 'Passowrd *'),
        $this->assertSame(32, strlen('fa01084a06ad512c779784f5fe3d4af4'));
        //array('name' => 'active', 'field' => 'active', 'database' => 'customer', 'alias' => 'c', 'import' => 2, 'import_name' => 'Active (0/1)'),
        $this->assertSame($this->row['active'], '1');
        //array('name' => 'optin', 'field' => 'optin', 'database' => 'customer', 'alias' => 'c', 'import' => 10, 'import_name' => 'Opt-in (0/1)'),
        $this->assertSame($this->row['optin'], '1');
        //array('name' => 'date add', 'field' => 'date_add', 'database' => 'customer', 'alias' => 'c', 'import' => 11, 'import_name' => 'Registration date (yyyy-mm-dd)'),
        $this->assertSame(true, $this->check_your_datetime($this->row['date add']));
        //array('name' => 'default group id', 'field' => 'id_defualt_group', 'database' => 'customer', 'alias' => 'c', 'import' => 12, 'import_name' => 'Default group ID'),
        $this->assertSame($this->row['default group id'], '3');
        //array('name' => 'groups', 'field' => 'groups', 'database' => 'other', 'import' => 13, 'import_name' => 'Groups (x,y,z...)'),
        $this->assertSame($this->row['groups'], 'Customer');
        //array('name' => 'address company', 'field' => 'address_company', 'database' => 'address', 'as' => true, 'alias' => 'a'),
        $this->assertSame($this->row['address company'], 'My Company');
        //array('name' => 'address firstname', 'field' => 'address_firstname', 'database' => 'address', 'as' => true, 'alias' => 'a'),
        $this->assertSame($this->row['address firstname'], 'John');
        //array('name' => 'address lastname', 'field' => 'address_lastname', 'database' => 'address', 'as' => true, 'alias' => 'a'),
        $this->assertSame($this->row['address lastname'], 'DOE');
        //array('name' => 'address address1', 'field' => 'address1', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address address1'], '16, Main street');
        //array('name' => 'address address2', 'field' => 'address2', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address address2'], '2nd floor');
        //array('name' => 'address postcode', 'field' => 'postcode', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address postcode'], '75002');
        //array('name' => 'address city', 'field' => 'city', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address city'], 'Paris ');
        //array('name' => 'address other', 'field' => 'other', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address other'], '');
        //array('name' => 'address phone', 'field' => 'phone', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address phone'], '0102030405');
        //array('name' => 'address phone_mobile', 'field' => 'phone_mobile', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address phone mobile'], '');
        //array('name' => 'address vat_number', 'field' => 'vat_number', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address vat number'], '');
        //array('name' => 'address dni', 'field' => 'dni', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address dni'], '');
        //array('name' => 'address active', 'field' => 'active', 'database' => 'address', 'alias' => 'a'),
        $this->assertSame($this->row['address active'], '1');
        //array('name' => 'address state', 'field' => 'name', 'database' => 'state', 'alias' => 's'),
        $this->assertSame($this->row['address state'], '');
        //array('name' => 'address country', 'field' => 'country_name', 'database' => 'country_lang', 'alias' => 'co', 'as' => true),
        $this->assertSame($this->row['address country'], 'France');
    }

    /**
     * @param $type
     * @return AdvancedExportClass
     */
    public function createModelWithAllFieldsAndDefaultSettings($type)
    {
        $aec = null;
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'advancedexport WHERE type = "' . $type . '"
                AND filename = "test_' . $type . '"';

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
            $aec->filename = 'test_' . $type;
            $aec->file_format = 'csv';
            $aec->fields = Tools::jsonEncode(
                [
                    'fields[]' => $this->getFieldsNames($type)
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
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'advancedexportfield WHERE tab = "' . $type . '"';
        $result = Db::getInstance()->ExecuteS($query);

        $return = [];
        foreach ($result as $field) {
            $return[$field['field']] = array($field['name']);
        }
        return $return;
    }
}
