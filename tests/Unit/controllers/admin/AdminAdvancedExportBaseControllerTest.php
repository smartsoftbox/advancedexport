<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

namespace Tests\Unit;

use AdminAdvancedExportModelController;
use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../../../../controllers/admin/AdminAdvancedExportBaseController.php';
require_once dirname(__FILE__) . '/../../../../classes/ModuleTools.php';


class AdminAdvancedExportBaseControllerTest extends testcase
{
    const className = 'AdminAdvancedExportBaseController';
    const moduleTools = 'ModuleTools';

    public function testGetEntity_Should_Return_Products_By_Default_Type_Is_False()
    {
        //arrange
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        $moduleTools = $this->createPartialMock(self::moduleTools, array('getValue'));
        $moduleTools->expects($this->once())
            ->method('getValue')
            ->with($this->equalTo('type'))
            ->willReturn(false);

        $adminAdvancedExportBaseController->setModuleTools($moduleTools);

        //act
        $type = $adminAdvancedExportBaseController->getEntity();

        //assert
        $this->assertSame('products', $type);
    }

    public function testGetEntity_Should_Return_Orders_Current_Tab_Id_Is_Orders_And_Type_Is_0_And_Ajax_0()
    {
        //arrange
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        $moduleTools = $this->createPartialMock(self::moduleTools, array('getValue'));
        $moduleTools->expects($this->once())
            ->method('getValue')
            ->with($this->equalTo('type'))
            ->willReturn(false);

        $_COOKIE['current_tab_id'] = 'orders';

        $adminAdvancedExportBaseController->setModuleTools($moduleTools);

        //act
        $type = $adminAdvancedExportBaseController->getEntity();

        //assert
        $this->assertSame('orders', $type);
    }

    public function testGetEntity_Should_Return_Orders_Type_Is_Orders_And_Ajax_0()
    {
        //arrange
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        $moduleTools = $this->createPartialMock(self::moduleTools, array('getValue', 'getCookie'));
        $moduleTools->expects($this->once())
            ->method('getValue')
            ->with($this->equalTo('type'))
            ->willReturn('orders');

        $moduleTools->expects($this->never())
            ->method('getCookie');

        $adminAdvancedExportBaseController->setModuleTools($moduleTools);

        //act
        $type = $adminAdvancedExportBaseController->getEntity();

        //assert
        $this->assertSame('orders', $type);
    }

    public function testGetLabelsAndFieldsArray_Should_Return_Mapped_Fields_When_Is_Not_Null_And_Get_Value_Is_Null()
    {
        //arrange
        $labels = array(
            'name' => '',
            'description' => ''
        );
        $mapping = array(
            'name' => 'name1'
        );
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        $moduleTools = $this->createPartialMock(self::moduleTools, array('getValue'));

        $adminAdvancedExportBaseController->setModuleTools($moduleTools);

        //act
        $fields_value = $adminAdvancedExportBaseController->getLabelsAsFieldsArray($labels, $mapping);

        //assert
        $this->assertSame(array('fields[name]' => 'name1', 'fields[description]' => ''), $fields_value);
    }

    public function testGetLabelsAndFieldsArray_Should_Return_Fields_From_Get()
    {
        //arrange
        $labels = array(
            'name' => '',
            'description' => ''
        );
        $mapping = array(
            'name' => 'name1'
        );
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        $moduleTools = $this->createPartialMock(self::moduleTools, array('getValue'));
        $moduleTools->expects($this->exactly(2))
            ->method('getValue')
            ->withConsecutive(['fields[name]'], ['fields[description]'])
            ->willReturnOnConsecutiveCalls('test', 'tests');

        $adminAdvancedExportBaseController->setModuleTools($moduleTools);

        //act
        $fields_value = $adminAdvancedExportBaseController->getLabelsAsFieldsArray($labels, $mapping);

        //assert
        $this->assertSame(array('fields[name]' => 'test', 'fields[description]' => 'tests'), $fields_value);
    }

    public function testGetLabelsAndFieldsArray_Should_Return_Empty_Array_When_Labels_Is_Null()
    {
        //arrange
        $labels = null;
        $mapping = array();

        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $fields_value = $adminAdvancedExportBaseController->getLabelsAsFieldsArray($labels, $mapping);

        //assert
        $this->assertSame(array(), $fields_value);
    }

    public function testGetProtocol_Should_Return_FTP_When_Export_Is_True_End_Save_Type_1()
    {
        //arrange
        $params = array (
          'export' => true,
          'save_type' => 1
        );
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $protocol = $adminAdvancedExportBaseController->getProtocol($params);

        //assert
        $this->assertSame('FTP', $protocol);
    }

    public function testGetProtocol_Should_Return_SFTP_When_Export_Is_True_End_Save_Type_2()
    {
        //arrange
        $params = array (
            'export' => true,
            'save_type' => 2
        );
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $protocol = $adminAdvancedExportBaseController->getProtocol($params);

        //assert
        $this->assertSame('SFTP', $protocol);
    }

    public function testGetProtocol_Should_Return_FTP_When_Export_Is_Null_End_Save_Type_3()
    {
        //arrange
        $params = array (
            'import_from' => 3
        );
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $protocol = $adminAdvancedExportBaseController->getProtocol($params);

        //assert
        $this->assertSame('FTP', $protocol);
    }

    public function testGetProtocol_Should_Return_SFTP_When_Export_Is_Null_End_Save_Type_2()
    {
        //arrange
        $params = array (
            'import_from' => 2
        );
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $protocol = $adminAdvancedExportBaseController->getProtocol($params);

        //assert
        $this->assertSame('SFTP', $protocol);
    }

    public function testIsGreaterOrEqualThenPrestaShopVersion_Should_Return_True_When_Version_Is_Bigger_Then_Parameter()
    {
        //arrange
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $is_bigger = $adminAdvancedExportBaseController->isGreaterOrEqualThenPrestaShopVersion(1.6);

        //assert
        $this->assertTrue($is_bigger);
    }

    public function testIsGreaterOrEqualThenPrestaShopVersion_Should_Return_False_When_Version_Is_Not_Bigger_Then_Parameter()
    {
        //arrange
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());

        //act
        $is_bigger = $adminAdvancedExportBaseController->isGreaterOrEqualThenPrestaShopVersion(1.8);

        //assert
        $this->assertFalse($is_bigger);
    }

    public function testAddToGet_Should_Add_Get_Value()
    {
        //arrange
        $adminAdvancedExportBaseController = $this->createPartialMock(self::className, array());
        // No mock I want test if it is exactly added to $_GET
        $adminAdvancedExportBaseController->setModuleTools(new \ModuleTools());

        //act
        $result = $adminAdvancedExportBaseController->addToGet('test', 1);

        //assert
        //It is pass as query string that's wy equals not same
        $this->assertEquals(1, $result);
    }
}
