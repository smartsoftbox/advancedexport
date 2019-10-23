<?php

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
use Context;
use Advancedexport;
use UpgradeHelper;
use AdvancedExportClass;
use Configuration;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../advancedexport.php';
require_once dirname(__FILE__) . '/../../upgrade/install-4.3.0.php';
require_once dirname(__FILE__) . '/../../upgrade/install-4.3.7.php';
require_once dirname(__FILE__) . '/../../upgrade/install-4.3.8.php';
require_once dirname(__FILE__) . '/../../upgrade/install-4.4.0.php';
require_once dirname(__FILE__) . '/../../upgrade/UpgradeHelper.php';

class Install_4_4_0Test extends IntegrationTestCase
{
    public $module;

    /**
     * Provide sensible defaults for tests that don't specify them.
     */
    public function setUp()
    {
        parent::setUp();

        $this->module = new Advancedexport();
    }

    public function test_findKeyByValue()
    {
        $key = findKeyByValue(array('test' => array('field' => 'product_id')), 'field', 'product_id');
        $this->assertSame('test', $key);
    }

    public function test_upgrade_module_4_4_0()
    {
        $return = array();
        $return[] = upgrade_module_4_3_0($this->module);
        $return[] = upgrade_module_4_3_7($this->module);
        $return[] = upgrade_module_4_3_8($this->module);
        $return[] = upgrade_module_4_4_0($this->module);

        $this->assertSame(
            false,
            UpgradeHelper::isColumnWithValueExists('name', 'AdvancedExport_CURRENT', 'configuration')
        );
        $this->assertSame(
            false,
            UpgradeHelper::isColumnWithValueExists('name', 'AdvancedExport_TOTAL', 'configuration')
        );

        $this->assertSame(
            true,
            UpgradeHelper::isColumnExists('ftp_directory', 'advancedexport')
        );
        $this->assertSame(
            true,
            UpgradeHelper::isColumnExists('ftp_port', 'advancedexport')
        );

        $this->assertSame(
            true,
            UpgradeHelper::isColumnExists('group15', 'advancedexportfield')
        );
        $this->assertSame(
            true,
            UpgradeHelper::isColumnExists('group17', 'advancedexportfield')
        );

        $this->assertSame(
            true,
            UpgradeHelper::isTableExists('advancedexportcron')
        );

        $this->assertEquals(array(true, true, true, true), $return);

        foreach ($this->module->export_types as $export_type) {
            foreach ($this->module->$export_type as $field) {
                $field['table'] = $field['database'];
                unset($field['database']);
                foreach ($field as $key => $value) {
                    $this->assertSame(
                        true,
                        UpgradeHelper::isColumnAndTabWithValueExists($key, $export_type, $value, 'advancedexportfield'),
                        'field name ' . $field['field'] . ' with column '. $key . ' under tab ' . $export_type .
                        ' with value ' . $value .  ' does\'t exists'
                    );
                }
            }
        }
    }
}
