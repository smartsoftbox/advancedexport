<?php
/**
 * 2020 smart soft.
 *
 * @author    marcin kubiak
 * @copyright smart soft
 * @license   commercial license
 *  international registered trademark & property of smart soft
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once dirname(__file__) . '/../../../../advancedexport.php';
require_once dirname(__file__) . '/../../../../classes/List/ImportList.php';

class ImportListTest extends testcase
{
    const ADVANCED_EXPORT = 'Advancedexport';

    /**
     * test for addImportData
     */
    public function testAddImportData_should_Add_Custom_As_AdvancedExport_Name_When_Custom_Import_True()
    {
        //arrange
        $mock = $this->createPartialMock('ImportList', array());

        $import = array();
        $import['custom'] = true;

        //act
        $result = $mock->addImportData($import);

        //assert
        $this->assertSame($result['advancedexport'], 'custom');
    }

    public function testAddImportData_should_AdvancedExport_Entity_Be_AdvancedExportImport_Entity_When_Custom_Import_True(
    )
    {
        //arrange
        $mock = $this->createPartialMock('ImportList', array());

        $import = array();
        $import['custom'] = true;
        $import['entity'] = 'products';

        //act
        $result = $mock->addImportData($import);

        //assert
        $this->assertSame($result['entity'], $import['entity']);
    }

    public function testAddImportData_should_Add_AdvancedExport_Name_When_Custom_Import_False()
    {
        //arrange
        $mock = $this->createPartialMock('ImportList', array());

        $import = array();
        $import['custom'] = false;
        $import['advancedexport_name'] = 'tests';
        $import['type'] = 'products';

        //act
        $result = $mock->addImportData($import);

        //assert
        $this->assertSame($result['advancedexport'], 'tests');
    }

    public function testAddImportData_should_Add__AdvancedExport_Type_As_Entity_When_Custom_Import_False()
    {
        //arrange
        $mock = $this->createPartialMock('ImportList', array());

        $import = array();
        $import['custom'] = false;
        $import['advancedexport_name'] = 'tests';
        $import['type'] = 'products';

        //act
        $result = $mock->addImportData($import);

        //assert
        $this->assertSame($result['entity'], 'products');
    }

    public function testListFieldsForm_should_id_advancedexportimport_Key_Exists()
    {
        //arrange
        $ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createPartialMock('ImportList', array('l'));
        $mock->setAdvancedExport($ae);

        //act
        $result = $mock->listFieldsForm();
        $result_keys = array_keys($result);
        //assert
        $this->assertSame($result_keys[0], 'id_advancedexportimport');
    }

    public function testListFieldsForm_should_name_Key_Exists()
    {
        //arrange
        $ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createPartialMock('ImportList', array('l'));
        $mock->setAdvancedExport($ae);

        //act
        $result = $mock->listFieldsForm();
        $result_keys = array_keys($result);
        //assert
        $this->assertSame($result_keys[1], 'name');
    }

    public function testListFieldsForm_should_entity_Key_Exists()
    {
        //arrange
        $ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createPartialMock('ImportList', array('l'));
        $mock->setAdvancedExport($ae);

        //act
        $result = $mock->listFieldsForm();
        $result_keys = array_keys($result);
        //assert
        $this->assertSame($result_keys[2], 'entity');
    }

    public function testListFieldsForm_should_advancedexport_Key_Exists()
    {
        //arrange
        $ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createPartialMock('ImportList', array('l'));
        $mock->setAdvancedExport($ae);

        //act
        $result = $mock->listFieldsForm();
        $result_keys = array_keys($result);
        //assert
        $this->assertSame($result_keys[3], 'advancedexport');
    }
}
