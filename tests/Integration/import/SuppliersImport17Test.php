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

class SuppliersImport17Test extends IntegrationTestCase
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

        $this->entity = 'suppliers';
        $adminAdvancedExportModelController = $this->createPartialMock('AdminAdvancedExportModelController', array());
        $this->row = HelperImport::getExportFileAsCsv($adminAdvancedExportModelController, $this->entity);
    }

    public function test_CsvImportFileData()
    {
        $this->assertSame($this->row['ID'], '1');
        $this->assertSame($this->row['Active (0/1)'], '1');
        $this->assertSame($this->row['Name *'], 'Studio Design');
        $this->assertSame($this->row['Description'], '<p><span style="font-size:10pt;font-style:normal;">Studio Design offers a range of items from ready-to-wear collections to contemporary objects. The brand has been presenting new ideas and trends since its creation in 2012.</span></p>');
        $this->assertSame($this->row['Short description'], ''); // reduction_percent
        $this->assertSame($this->row['Meta title'], ''); // reduction_percent
        $this->assertSame($this->row['Meta keywords'], '');
        $this->assertSame($this->row['Meta description'], '');
        $this->assertSame($this->row['Image URL'], 'http://prestashop-git//opt/project/img/m/1.jpg');
//        $this->assertSame($this->row['ID / Name of group shop'], '3');
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
