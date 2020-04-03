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

use AdminAdvancedExportImportController;
use AdvancedExportImportClass;
use AdvancedExportClass;
use PHPUnit\Framework\TestCase;
use PrestaShopException;
require_once dirname(__FILE__) . '/../../../../controllers/admin/AdminAdvancedExportImportFileController.php';
require_once dirname(__FILE__) . '/../../../../classes/Model/AdvancedExportImportClass.php';
require_once dirname(__FILE__) . '/../../../../classes/Model/AdvancedExportClass.php';

class AdminAdvancedExportImportFileControllerTest extends testcase
{
    const className = 'AdminAdvancedExportImportFileController';
    const aeImportClassName = 'AdvancedExportImportClass';
    const moduleTools = 'ModuleTools';

    public function testGetToolBarTitle_Should_Return_ToolBar_Title()
    {
        //arrange
        $adminAdvancedExportImportFileController =
            $this->createPartialMock(self::className, array('l'));

        $adminAdvancedExportImportFileController->expects($this->exactly(2))
            ->method('l')
            ->withConsecutive(['Files used for'], ['import model id'])
            ->willReturnOnConsecutiveCalls('Files used for', 'import model id');

        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->name = 'test';
        $aeImport->id = 1;

        //act
        $toolbar_title = $adminAdvancedExportImportFileController->getToolBarTitle($aeImport);

        //assert
        $this->assertSame('Files used for test import model id 1', $toolbar_title);
    }

    public function testGetImportId_Should_Add_Id_Import_To_Cookie_Name_id_advancedexportimport()
    {
        //arrange
        $adminAdvancedExportImportFileController =
            $this->createPartialMock(self::className, array());

        $moduleTools =
            $this->createPartialMock(self::moduleTools, array('getCookieObject'));

        $cookie =
            $this->createPartialMock('Cookie', array('write'));

        $cookie->expects($this->once())
            ->method('write')
            ->willReturn(true);

        $moduleTools->expects($this->once())
            ->method('getCookieObject')
            ->willReturn($cookie);

        $adminAdvancedExportImportFileController->setModuleTools($moduleTools);

        //act
        $id_import = $adminAdvancedExportImportFileController->getImportId(1);

        //assert
        $this->assertSame(1, $id_import);
        $this->assertSame(1, $cookie->id_advancedexportimport);
    }

    public function testGetImportId_Should_Get_Id_Import_From_Cookie_Name_id_advancedexportimport_If_Id_Import_Is_Null()
    {
        //arrange
        $adminAdvancedExportImportFileController =
            $this->createPartialMock(self::className, array());

        $moduleTools =
            $this->createPartialMock(self::moduleTools, array('getCookieObject'));

        $cookie =
            $this->createPartialMock('Cookie', array('write'));

        $cookie->id_advancedexportimport = 2;

        $cookie->expects($this->never())
            ->method('write')
            ->method($this->anything());

        $moduleTools->expects($this->once())
            ->method('getCookieObject')
            ->willReturn($cookie);

        $adminAdvancedExportImportFileController->setModuleTools($moduleTools);

        //act
        $id_import = $adminAdvancedExportImportFileController->getImportId(false);

        //assert
        $this->assertSame(2, $id_import);
    }
}
