<?php

namespace LegacyTests\TestCase;

use Employee;
use PrestaShopBundle\Install\DatabaseDump;
use Context;
use AdminAdvancedExportModelController;
use Export;
use AdvancedExportClass;
use Configuration;
use Product;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../controllers/admin/AdminAdvancedExportModelController.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportImportClass.php';
require_once dirname(__FILE__) . '/../../classes/Model/AdvancedExportCronClass.php';
require_once dirname(__FILE__) . '/../../classes/Field/CustomFields.php';
require_once dirname(__FILE__) . '/../../classes/Data/ImportFrom.php';

class ProductsCronImportTest extends IntegrationTestCase
{
    const PRODUCTS = 'products';
    private static $dump;
    private $aeModelController;
    private $export;
    private $row;
    private $entity;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../config/config.inc.php';
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

        $id_ae = $this->createProductsDefaultExportModel();
        $id_ae_import = $this->createImportModel($id_ae);
        $this->createCronModel($id_ae_import);
        $this->runCron();
        $this->checkCron();
    }

    /**
     * @param $x
     * @return bool
     */
    function check_your_datetime($x)
    {
        return (date('Y-m-d H:i:s', strtotime($x)) == $x);
    }

    private function createProductsDefaultExportModel()
    {
        $this->aeModelController = $this->createPartialMock(
            'AdminAdvancedExportModelController',
            array()
        );

        // create export models for import
        $advancedExportClass = $this->aeModelController->generateDefaultCsvByType($this->entity);

        $this->export = new Export();

        // run export
        $this->export->createExportFile($advancedExportClass);
        // read files
//        return  _PS_ROOT_DIR_ . '/modules/advancedexport/csv/' . self::PRODUCTS . '/' . self::PRODUCTS . '_import.csv';
        return $advancedExportClass;
    }

    private function createImportModel($id_ae)
    {
        $import = new \AdvancedExportImportClass();
        $import->entity = self::PRODUCTS;
        $import->name = 'test';
        $import->import_from = \ImportFrom::getImportFromIdByName(self::PRODUCTS);
        $import->import_filename = 'products_import.csv';
        $import->filename = '';
        $import->file_token = '';
        $import->url = '';
        $import->id_advancedexport = $id_ae;
        $import->ftp_user_name = '';
        $import->ftp_hostname = '';
        $import->ftp_user_pass = '';
        $import->ftp_directory = '';
        $import->ftp_port = '';
        $import->iso_lang = 'en';
        $import->separator = ',';
        $import->multi_value_separator = ';';
        $import->truncate = true;
        $import->regenerate = false;
        $import->match_ref = false;
        $import->forceIDs = true;
        $import->send_email = 'smartsoftbox@gmail.com';
        $import->skip = true;
        $import->mapping = '';
        $import->save();

        return $import->id;
    }

    private function checkCron()
    {
    }

    private function runCron()
    {
    }

    private function createCronModel($id_ae_import)
    {
        $cron = new \AdvancedExportCronClass();
        $cron->is_import = '';
        $cron->id_model = '';
        $cron->type = '';
        $cron->name = '';
        $cron->cron_hour = '';
        $cron->cron_day = '';
        $cron->cron_week = '';
        $cron->cron_month = '';
        $cron->last_export = '';
        $cron->active = '';
        $cron->save();

        return $cron->id;
    }
}
