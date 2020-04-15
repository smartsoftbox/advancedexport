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

require_once dirname(__FILE__) . '/../../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../../controllers/admin/AdminAdvancedExportModelController.php';
require_once dirname(__FILE__) . '/../../../controllers/admin/AdminAdvancedExportImportController.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportClass.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportFieldClass.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportImportClass.php';
require_once dirname(__FILE__) . '/../../../classes/Model/AdvancedExportCronClass.php';
require_once dirname(__FILE__) . '/../../../classes/Field/CustomFields.php';
require_once dirname(__FILE__) . '/../../../classes/Data/ImportFrom.php';
require_once dirname(__FILE__) . '/../../../classes/ModuleTools.php';

class ProductsCronImportUrlTest extends IntegrationTestCase
{
    const PRODUCTS = 'products';
    private static $dump;
    private $aeModelController;
    private $export;
    private $row;
    private $entity;
    public $http;

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

        $this->cleanCronTable();
        $id_ae = $this->createProductsDefaultExportModel();
        $id_ae_import = $this->createImportModel();
        $this->createCronModel($id_ae_import);
        $this->http = $this->runCron();
    }

    public function cleanCronTable()
    {
        DB::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'advancedexportcron');
    }

    private function createProductsDefaultExportModel()
    {
        $this->aeModelController = $this->createPartialMock(
            'AdminAdvancedExportModelController',
            array()
        );

        // create export models for import
        $advancedExportClass = $this->aeModelController->generateDefaultCsvForImport(self::PRODUCTS);

        $this->export = new Export();
        // run export
        $this->export->createExportFile($advancedExportClass);
        // read files
//        return  _PS_ROOT_DIR_ . '/modules/advancedexport/csv/' . self::PRODUCTS . '/' . self::PRODUCTS . '_import.csv';
        return $advancedExportClass->id;
    }

    private function createImportModel()
    {
        $import = new \AdvancedExportImportClass();
        $import->entity = 1;
        $import->name = 'test';
        $import->import_from = \ImportFrom::getImportFromIdByName('url');
        $import->import_filename = 'products_import.csv';
        $import->filename = '';
        $import->file_token = '';
        $import->url = 'http://prestashop-git/modules/advancedexport/csv/products/products_import.csv';
        $import->id_advancedexport = 0;
        $import->ftp_user_name = '';
        $import->ftp_hostname = '';
        $import->ftp_user_pass = '';
        $import->ftp_directory = '';
        $import->ftp_port = '';
        $import->iso_lang = 'en';
        $import->separator = ',';
        $import->multi_value_separator = ';';
        $import->truncate = false;
        $import->regenerate = false;
        $import->match_ref = false;
        $import->forceIDs = true;
        $import->send_email = false;
        $import->skip = true;
        $import->mapping = '["id","active","name","category","price_tex","price_tin","id_tax_rules_group","wholesale_price","on_sale","reduction_price","reduction_percent","reduction_from","reduction_to","reference","supplier_reference","supplier","manufacturer","ean13","upc","mpn","ecotax","width","height","depth","weight","delivery_in_stock","delivery_out_stock","quantity","minimal_quantity","low_stock_threshold","low_stock_alert","visibility","additional_shipping_cost","unity","unit_price","description_short","description","tags","meta_title","meta_keywords","meta_description","link_rewrite","available_now","available_later","available_for_order","available_date","date_add","show_price","image_alt","image_alt","features","online_only","condition","customizable","uploadable_files","text_fields","out_of_stock","is_virtual","file_url","nb_downloadable","date_expiration","nb_days_accessible","shop","advanced_stock_management","depends_on_stock","warehouse","accessories"]';
        $import->save();

        $adminAEImport = $this->adminAdvancedExportModelController = $this->createPartialMock(
            'AdminAdvancedExportImportController',
            array()
        );
        $adminAEImport->setModuleTools(new \ModuleTools());

        $adminAEImport->createImportFolder($import->id);
        $adminAEImport->getImportPath($import, true);
        chmod(_AE_IMPORT_PATH_ . $import->id, 0777);

        return $import->id;
    }

    public function test_CheckCron()
    {
        $finish = '{"isFinished":true,"doneCount":181,"totalCount":181,"crossStepsVariables":{"accessories":[]},"nextPostSize":65554,"postSizeLimit":134217728,"oneMoreStep":1,"moreStepLabel":"Linking Accessories..."}';
        $this->assertEquals($finish, $this->http);
    }

    private function runCron()
    {
        $cron_url = 'http://prestashop-git/index.php?secure_key=';
        $cron_url .= Configuration::getGlobalValue('ADVANCEDEXPORT_SECURE_KEY');
        $cron_url .= '&fc=module&module=advancedexport&controller=cron&id_lang=1';

        $http  = $this->getWebPage($cron_url);

        return $http;
    }

    private function createCronModel($id_ae_import)
    {
        $cron = new \AdvancedExportCronClass();
        $cron->is_import = true;
        $cron->id_model = $id_ae_import;
        $cron->type = ''; // todo check if you still need this field
        $cron->name = 'test';
        $cron->cron_hour = '*';
        $cron->cron_day = '*';
        $cron->cron_week = '*';
        $cron->cron_month = '*';
        $cron->last_export = '';
        $cron->active = true;
        $cron->save();

        return $cron->id;
    }

    function getWebPage($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_USERAGENT      => "test", // name of client
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $content  = curl_exec($ch);

        curl_close($ch);

        return $content;
    }
}
