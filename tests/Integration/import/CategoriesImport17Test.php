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

class CategoriesImport17Test extends IntegrationTestCase
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

        $this->entity = 'categories';
        $adminAdvancedExportModelController = $this->createPartialMock('AdminAdvancedExportModelController', array());
        $this->row = HelperImport::getExportFileAsCsv($adminAdvancedExportModelController, $this->entity);
    }

    public function test_CsvImportFileData()
    {
        $this->assertSame($this->row['ID'], '1');
        $this->assertSame($this->row['Active (0/1)'], '1');
        $this->assertSame($this->row['Name'], 'Root');
        $this->assertSame($this->row['Parent category'], '0');
//        $this->assertSame($this->row['Root category (0/1'], 'prestashop'); // reduction_price
        $this->assertSame($this->row['Description'], ''); // reduction_percent
        $this->assertSame($this->row['Meta title'], ''); // reduction_percent
        $this->assertSame($this->row['Meta keywords'], '');
        $this->assertSame($this->row['Meta description'], '');
        $this->assertSame($this->row['URL rewritten'], 'root');
        $this->assertSame($this->row['Image URL'], 'http://prestashop-git/img/p/.jpg');
//        $this->assertSame($this->row['ID / Name of shop'], '3');
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
