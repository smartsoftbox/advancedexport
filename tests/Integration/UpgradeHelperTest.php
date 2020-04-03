<?php

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
use Context;
use UpgradeHelper;

;

use Configuration;
use Product;
use Tools;
use Db;

require_once dirname(__FILE__) . '/../../upgrade/UpgradeHelper.php';

class UpgradeHelperTest extends IntegrationTestCase
{
    /**
     * Provide sensible defaults for tests that don't specify them.
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function test_isColumnExists()
    {
        //assert
        $this->assertSame(true, UpgradeHelper::isColumnExists('field', 'advancedexportfield'));
        $this->assertSame(false, UpgradeHelper::isColumnExists('group', 'advancedexportfield'));
    }

    public function test_isColumnWithValueExists()
    {
        //assert
        $this->assertSame(
            true,
            UpgradeHelper::isColumnWithValueExists('field', 'product_id', 'advancedexportfield')
        );
        $this->assertSame(
            false,
            UpgradeHelper::isColumnWithValueExists('field', 'test', 'advancedexportfield')
        );
    }

    public function test_insertColumn()
    {
        //assert
        $this->assertSame(
            true,
            UpgradeHelper::insertColumn('group15', 'alias', 'advancedexportfield')
        );
        $this->assertSame(true, UpgradeHelper::isColumnExists('group15', 'advancedexportfield'));
    }

    public function test_insertField()
    {
        //arrange
        $field = array(
            'name' => 'Product Id',
            'field' => 'test',
            'table' => 'test',
            'import' => 1,
            'import_combination' => 1,
            'import_combination_name' => 'test*',
            'import_name' => 'test',
            'alias' => 't',
            'group15' => 'Information'
        );

        //assert
        $this->assertSame(
            true,
            UpgradeHelper::insertField($field, 'advancedexportfield')
        );

        $this->assertSame(
            true,
            UpgradeHelper::isColumnWithValueExists('group15', 'Information', 'advancedexportfield')
        );
    }
}
