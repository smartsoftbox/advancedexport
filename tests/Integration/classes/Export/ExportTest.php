<?php

namespace LegacyTests\TestCase;

use PrestaShopBundle\Install\DatabaseDump;
use Exception;
use Address;
use Carrier;
use Cart;
use CartRule;
use Configuration;
use Context;
use Currency;
use Db;
use Group;
use Order;
use PrestaShopBundle\Security\Admin\Employee;
use PrestaShopDatabaseException;
use Product;
use Tools;
use Tax;
use TaxRulesGroup;
use TaxRule;
use Export;
use Validate;
use Customer;
use AdvancedExportClass;

require_once dirname(__FILE__) . '/../../../../classes/Export/Export.php';
require_once dirname(__FILE__) . '/../../../../classes/Model/AdvancedExportClass.php';

class ExportTest extends IntegrationTestCase
{
    private $ae;
    const className = 'Export';

    public static function setUpBeforeClass()
    {
        // parent::setUpBeforeClass();
        // Some tests might have cleared the configuration
        // Configuration::loadConfiguration();
        require_once __DIR__ . '/../../../../../../config/config.inc.php';
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
    }


    public function testRoundValues_Should_Not_Change_Round_When_Decimal_Round_Is_Minus_one()
    {
        //arrange
        $object = array();
        $object['price'] = 12.55;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = -1;

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(12.55, $object['price']);
    }

    public function testRoundValues_Should_Not_Change_Round_When_Decimal_Round_Is_Minus_one_String()
    {
        //arrange
        $object = array();
        $object['price'] = 12.55;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = '-1';

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(12.55, $object['price']);
    }

    public function testRoundValues_Should_Change_Round_When_Decimal_Round_Is_1()
    {
        //arrange
        $object = array();
        $object['price'] = 12.55;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = 1;

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(12.6, $object['price']);
    }

    public function testRoundValues_Should_Change_Round_When_Decimal_Round_Is_0()
    {
        //arrange
        $object = array();
        $object['price'] = 12.5;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = 0;

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(13.0, $object['price']);
    }

    public function testRoundValues_Should_Change_Round_When_Decimal_Round_Is_1_string()
    {
        //arrange
        $object = array();
        $object['price'] = 12.5;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = '1';

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(12.5, $object['price']);
    }

    public function testRoundValues_Should_Change_Round_When_Decimal_Round_Is_1_String_And_Price_Is_String()
    {
        //arrange
        $object = array();
        $object['price'] = '12.5';

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = '1';

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(12.5, $object['price']);
    }
}
