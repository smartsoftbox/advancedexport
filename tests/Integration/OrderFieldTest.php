<?php

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
use Context;
use Advancedexport;
use UpgradeHelper;
use Fields418;
use Configuration;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../advancedexport.php';
require_once dirname(__FILE__) . '/Fields418.php';

class OrderFieldTest extends IntegrationTestCase
{
    public $module;

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../config/config.inc.php';
        Context::getContext()->employee = new \Employee(1);
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

        $this->module = new Advancedexport();
    }


    public function test_checkFieldsIn43HasSameOrderInLaterVersion()
    {
        $Fields418 = new Fields418();

        foreach ($Fields418->export_types as $export_type) {
            foreach ($Fields418->$export_type as $key => $field) {
                $this->assertSame($field['field'], $this->module->$export_type[$key]['field']);
            }
        }
    }
}
