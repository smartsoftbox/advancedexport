<?php

namespace LegacyTests\TestCase;

use Employee;
use PrestaShopBundle\Install\DatabaseDump;
use Context;
use Export;
use AdvancedExportClass;
use Configuration;
use Product;
use Tools;
use Db;
use AdminAdvancedExportModelController;

require_once dirname(__FILE__) . '/../../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../../tests/Integration/HelperImport.php';
require_once dirname(__FILE__) . '/../../../controllers/admin/AdminAdvancedExportModelController.php';

class CustomersImport17Test extends IntegrationTestCase
{
    private static $dump;
    private $row;
    private $entity;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../../config/config.inc.php';
        Context::getContext()->employee = new Employee(1);
    }

    public static function tearDownAfterClass()
    {
    }

    /**
     * Provide sensible defaults for tests that don't specify them.
     */
    public function setUp()
    {
        parent::setUp();

        $this->entity = 'customers';
        $adminAdvancedExportModelController = $this->createPartialMock('AdminAdvancedExportModelController', array());
        $this->row = HelperImport::getExportFileAsCsv($adminAdvancedExportModelController, $this->entity);
    }

    public function test_CsvImportFileData()
    {
        $this->assertSame($this->row['ID'], '1');
        $this->assertSame($this->row['Active (0/1)'], '0');
        $this->assertSame($this->row['Titles ID (Mr = 1 Ms = 2 else 0)'], '1');
        $this->assertSame($this->row['Email *'], 'anonymous@psgdpr.com');
        $this->assertSame($this->row['Passowrd *'], 'prestashop'); // reduction_price
        $this->assertSame($this->row['Birthday (yyyy-mm-dd)'], '0000-00-00'); // reduction_percent
        $this->assertSame($this->row['Last Name *'], 'Anonymous'); // reduction_percent
        $this->assertSame($this->row['First Name *'], 'Anonymous');
        $this->assertSame($this->row['Newsletter (0/1)'], '0');
        $this->assertSame($this->row['Opt-in (0/1)'], '1');
        $this->assertTrue($this->check_your_datetime($this->row['Registration date (yyyy-mm-dd)']));
        $this->assertSame($this->row['Groups (x y z...)'], 'Customer');
        $this->assertSame($this->row['Default group ID'], '3');
//        $this->assertSame($this->row['ID / Name of shop'], '');
    }

    /**
     * @param $x
     * @return bool
     */
    function check_your_datetime($x)
    {
        return (date('Y-m-d H:i:s', strtotime($x)) == $x);
    }
}
