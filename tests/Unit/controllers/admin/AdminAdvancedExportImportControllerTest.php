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
require_once dirname(__FILE__) . '/../../../../controllers/admin/AdminAdvancedExportImportController.php';
require_once dirname(__FILE__) . '/../../../../classes/Model/AdvancedExportImportClass.php';
require_once dirname(__FILE__) . '/../../../../classes/Model/AdvancedExportClass.php';

/**
 * @covers AdminAdvancedExportImportController
 */
class AdminAdvancedExportImportControllerTest extends testcase
{
    const className = 'AdminAdvancedExportImportController';
    const aeImportClassName = 'AdvancedExportImportClass';
    const moduleTools = 'ModuleTools';

    public function testGetSeparator_Should_Add_Default_Separator_To_Form_Fields_When_Separator_Is_Not_Set()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());

        //act
        $adminAdvancedExportImportController->getSeparator($aeImport);

        //assert
        $this->assertSame(',', $adminAdvancedExportImportController->fields_value['separator']);
    }

    public function testGetSeparator_Should_Add_Default_Separator_To_Form_Fields_When_Separator_Is_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->separator = '';

        //act
        $adminAdvancedExportImportController->getSeparator($aeImport);

        //assert
        $this->assertSame(',', $adminAdvancedExportImportController->fields_value['separator']);
    }

    public function testGetSeparator_Should_Add_Default_Separator_To_Form_Fields_When_Separator_Is_NULL()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->separator = null;

        //act
        $adminAdvancedExportImportController->getSeparator($aeImport);

        //assert
        $this->assertSame(',', $adminAdvancedExportImportController->fields_value['separator']);
    }

    public function testGetSeparator_Should_Add_AEImport_Separator_To_Form_Fields_When_AE_Separator_Is_NOT_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->separator = '|';

        //act
        $adminAdvancedExportImportController->getSeparator($aeImport);

        //assert
        $this->assertSame('|', $adminAdvancedExportImportController->fields_value['separator']);
    }

    public function testGetMultiValueSeparator_Should_Add_Default_Multi_Field_Separator_To_Form_Fields_When_Multi_Field_Separator_Is_Not_Set()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());

        //act
        $adminAdvancedExportImportController->getMultiValueSeparator($aeImport);

        //assert
        $this->assertSame(';', $adminAdvancedExportImportController->fields_value['multi_value_separator']);
    }

    public function testGetMultiValueSeparator_Should_Add_Default_Multi_Field_Separator_To_Form_Fields_When_Multi_Field_Separator_Is_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->multi_value_separator = '';

        //act
        $adminAdvancedExportImportController->getMultiValueSeparator($aeImport);

        //assert
        $this->assertSame(';', $adminAdvancedExportImportController->fields_value['multi_value_separator']);
    }

    public function testGetMultiValueSeparator_Should_Add_Default_Multi_Field_Separator_To_Form_Fields_When_Multi_Field_Separator_Is_NULL()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->multi_value_separator = null;

        //act
        $adminAdvancedExportImportController->getMultiValueSeparator($aeImport);

        //assert
        $this->assertSame(';', $adminAdvancedExportImportController->fields_value['multi_value_separator']);
    }

    public function testGetMultiValueSeparator_Should_Add_AEImport_Multi_Field_Separator_To_Form_Fields_When_AEImport_Multi_Field_Separator_Is_NOT_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->multi_value_separator = '|';

        //act
        $adminAdvancedExportImportController->getMultiValueSeparator($aeImport);

        //assert
        $this->assertSame('|', $adminAdvancedExportImportController->fields_value['multi_value_separator']);
    }

    public function testGetTestConnection_Should_Return_Test_Connection_Button_Template()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());

        $moduleTools = $this->createPartialMock(self::moduleTools, array('fetch'));
        $moduleTools->expects($this->once())
            ->method('fetch')
            ->with($this->stringContains(
                'modules/advancedexport/views/templates/admin/test_connection_button.tpl'
            ))
            ->will($this->returnValue('test_connection_button'));

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $adminAdvancedExportImportController->getTestConnection();

        //assert
        $this->assertSame('test_connection_button', $adminAdvancedExportImportController->fields_value['test_connection']);
    }

    public function testGetExportFilePath_Should_Return_Export_File_Path()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->type = "products";
        $aeImport->filename = "test";
        $aeImport->file_format = 'csv';

        //act
        $path = $adminAdvancedExportImportController->getExportFilePath($aeImport);

        //assert
        $this->assertContains('modules/advancedexport/csv/products/test.csv', $path);
    }

    public function testGetExportFilePath_Should_Throw_Exception_When_Type_Is_Null()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $aeImport = $this->createPartialMock(self::aeImportClassName, array());
        $aeImport->type = null;

        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid export model.');

        //act
        $path = $adminAdvancedExportImportController->getExportFilePath($aeImport);
    }

    public function testAddListColumns_Should_Add_Import_From_Public_Name()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());

        $list = array(
            array(
                'import_from' => 0,
                'id_advancedexport' => 0
            )
        );

        //act
        $adminAdvancedExportImportController->addListColumns($list);

        //assert
        $this->assertSame('Export Model', $list[0]['import_from']);
    }

    public function testAddListColumns_Should_Add_Import_Export_Model_When_Id_Advancedexport_Is_1()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array('getAdvancedExportClass'));
        $ae = new AdvancedExportClass();
        $ae->name = 'test';

        $adminAdvancedExportImportController->expects($this->once())
            ->method('getAdvancedExportClass')
            ->with($this->equalTo(1))
            ->willReturn($ae);

        $list = array(
            array(
                'import_from' => 0,
                'id_advancedexport' => 1
            )
        );

        //act
        $adminAdvancedExportImportController->addListColumns($list);

        //assert
        $this->assertSame('test', $list[0]['export_model']);
    }

    public function testDisplayImportLink_Should_Return_Correct_Import_Action()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array('createTemplate', 'l'));
        $smarty = $this->createPartialMock('Smarty', array('assign', 'fetch'));
        $adminAdvancedExportImportController->table = 'advancedexportimport';

        $smarty->expects($this->once())
            ->method('assign')
            ->with($this->equalTo(array(
                    'href' => '&token=test&id_advancedexportimport=1&importadvancedexportimport=1',
                    'action' => 'Import',
                    'id' => 1,
                    'is_presta_16' => true,
            )))
            ->willReturn($smarty);
        $adminAdvancedExportImportController->expects($this->once())
            ->method('l')
            ->with($this->equalTo('Import'))
            ->willReturn('Import');
        $adminAdvancedExportImportController->expects($this->once())
            ->method('createTemplate')
            ->with($this->equalTo('helpers/list/list_action_import.tpl'))
            ->willReturn($smarty);


        //act
        $adminAdvancedExportImportController->displayImportLink('test', 1);
    }

    public function testAddImportGETValues_Should_Throw_Exception_When_Id_Is_Null()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());

        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid import id');

        //act
        $adminAdvancedExportImportController->addImportGETValues(null);
    }

    public function testAddImportGETValues_Should_Add_Correct_Properties_To_Get_When_Import_From_Is_0_And_ID_Advancedexport_Is_1()
    {
        //arrange
        $aeImport = new AdvancedExportImportClass();
        $aeImport->id = 1;
        $aeImport->import_from = 0;
        $aeImport->id_advancedexport = 1;
        $aeImport->iso_lang = 'en';

        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array(
                'getAdvancedExportImportClass',
                'addToGet',
                'addMappingToGet'
            ));

        $adminAdvancedExportImportController->expects($this->once())
            ->method('getAdvancedExportImportClass')
            ->with(1)
            ->willReturn($aeImport);

        $adminAdvancedExportImportController->expects($this->exactly(12))
            ->method('addToGet')
            ->withConsecutive(
                ['id', $aeImport->id],
                ['csv', $aeImport->filename],
                ['iso_lang', 'en'],
                ['regenerate', $aeImport->regenerate],
                ['entity', $aeImport->entity],
                ['sendemail', $aeImport->send_email],
                ['separator', $aeImport->separator],
                ['multi_value_separator', $aeImport->multi_value_separator],
                ['skip', $aeImport->skip],
                ['truncate', $aeImport->truncate],
                ['match_ref', $aeImport->match_ref],
                ['forceIDs', $aeImport->forceIDs]
            );

        $adminAdvancedExportImportController->expects($this->once())
            ->method('addMappingToGet')
            ->with($aeImport->mapping);

        //act
        $adminAdvancedExportImportController->addImportGETValues(1);
    }

    public function testAddMappingToGet_Should_Create_Correct_Query()
    {
        //arrange
        $mapping = json_encode(array(
           'id' => 'newId',
           'description' => 'newDescription'
        ));

        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array(
                'addToGETQuery',
            ));

        $adminAdvancedExportImportController->expects($this->once())
            ->method('addToGETQuery')
            ->with(array( 1 => 'type_value[id]=newId&type_value[description]=newDescription'));

        //act

        //assert
        $adminAdvancedExportImportController->addMappingToGet($mapping);
    }

    public function testDisplayAjaxImport_Should_Pass_Correct_Value_To_RunImport()
    {
        //arrange
        $aeImport = new AdvancedExportImportClass();
        $aeImport->id = 1;
        $aeImport->import_from = 0;
        $aeImport->id_advancedexport = 1;
        $aeImport->iso_lang = 'en';

        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array(
                'getAdvancedExportImportClass',
                'addImportGETValues',
                'runImport',
            ));

        $adminAdvancedExportImportController->expects($this->once())
            ->method('getAdvancedExportImportClass')
            ->with(1)
            ->willReturn($aeImport);

        $moduleTools = $this->createPartialMock(self::moduleTools, array('getValue'));
        $moduleTools->expects($this->exactly(5))
            ->method('getValue')
            ->withConsecutive(
                ['id'],
                ['offset'],
                ['limit'],
                ['validateOnly'],
                ['moreStep']
            )
            ->willReturnOnConsecutiveCalls(1, 0, 10, 0, 2);


        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        $adminAdvancedExportImportController->expects($this->once())
            ->method('addImportGETValues')
            ->with(1);

        $adminAdvancedExportImportController->expects($this->once())
            ->method('runImport')
            ->withConsecutive(
                [0, 10, false, 2]
            );

        //act
        $adminAdvancedExportImportController->ajaxProcessImport();
    }

    public function testIsImportFromIsExportModelAndIdIsNotEmpty_Should_Return_True_When_Import_From_Is_0_And_Id_Advancedexport_Is_Not_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $moduleTools =
            $this->createPartialMock(self::moduleTools, array('getValue'));

        $moduleTools->expects($this->exactly(2))
            ->method('getValue')
            ->withConsecutive(['import_from'], ['id_advancedexport'])
            ->willReturnOnConsecutiveCalls(0, 1);

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $is = $adminAdvancedExportImportController->isImportFromIsExportModelAndIdIsNotEmpty();

        //assert
        $this->assertTrue($is);
    }

    public function testIsImportFromIsExportModelAndIdIsNotEmpty_Should_Return_False_When_Import_From_Is_0_And_Id_Advancedexport_Is_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $moduleTools =
            $this->createPartialMock(self::className, array('getValue'));

        $moduleTools->expects($this->exactly(2))
            ->method('getValue')
            ->withConsecutive(['import_from'], ['id_advancedexport'])
            ->willReturnOnConsecutiveCalls(0, 0);

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $is = $adminAdvancedExportImportController->isImportFromIsExportModelAndIdIsNotEmpty();

        //assert
        $this->assertFalse($is);
    }

    public function testIsImportFromIsExportModelAndIdIsNotEmpty_Should_Return_False_When_Import_From_Is_1_And_Id_Advancedexport_Is_Empty()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $moduleTools =
            $this->createPartialMock(self::className, array('getValue'));

        $moduleTools->expects($this->exactly(2))
            ->method('getValue')
            ->withConsecutive(['import_from'], ['id_advancedexport'])
            ->willReturnOnConsecutiveCalls(0, 0);

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $is = $adminAdvancedExportImportController->isImportFromIsExportModelAndIdIsNotEmpty();

        //assert
        $this->assertFalse($is);
    }

    public function testIsImportFromIsExportModelAndIdIsNotEmpty_Should_Return_False_When_Import_From_Is_1_And_Id_Advancedexport_Is_Null()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());
        $moduleTools =
            $this->createPartialMock(self::className, array('getValue'));

        $moduleTools->expects($this->exactly(2))
            ->method('getValue')
            ->withConsecutive(['import_from'], ['id_advancedexport'])
            ->willReturnOnConsecutiveCalls(0, Null);

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $is = $adminAdvancedExportImportController->isImportFromIsExportModelAndIdIsNotEmpty();

        //assert
        $this->assertFalse($is);
    }

    public function testGetSeparatorForReader_Should_Return_Advancedexport_Delimiter_When_isImportFromIsExportModelAndIdIsNotEmpty_Return_True()
    {
        //arrange
        $advancedExport =
            $this->createPartialMock('AdvancedExportClass', array());
        $advancedExport->delimiter = ';';

        $adminAdvancedExportImportController =
            $this->createPartialMock(
                self::className,
                array('isImportFromIsExportModelAndIdIsNotEmpty', 'getAdvancedExportClass')
            );

        $adminAdvancedExportImportController->expects($this->once())
            ->method('isImportFromIsExportModelAndIdIsNotEmpty')
            ->willReturn(true);

        $adminAdvancedExportImportController->expects($this->once())
            ->method('getAdvancedExportClass')
            ->willReturn($advancedExport);

        $moduleTools =
            $this->createPartialMock(self::className, array('getValue'));

        $moduleTools->expects($this->exactly(2))
            ->method('getValue')
            ->withConsecutive(['separator'], ['id_advancedexport'])
            ->willReturnOnConsecutiveCalls(',', 1);

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $separator = $adminAdvancedExportImportController->getSeparatorForReader();

        //assert
        $this->assertSame(';', $separator);
    }

    public function testGetSeparatorForReader_Should_Not_Return_Advancedexport_Delimiter_When_isImportFromIsExportModelAndIdIsNotEmpty_Return_False()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(
                self::className,
                array('isImportFromIsExportModelAndIdIsNotEmpty', 'getAdvancedExportClass')
            );

        $adminAdvancedExportImportController->expects($this->once())
            ->method('isImportFromIsExportModelAndIdIsNotEmpty')
            ->willReturn(false);

        $adminAdvancedExportImportController->expects($this->never())
            ->method('getAdvancedExportClass');

        $moduleTools =
            $this->createPartialMock(self::className, array('getValue'));

        $moduleTools->expects($this->once())
            ->method('getValue')
            ->withConsecutive(['separator'], ['id_advancedexport'])
            ->willReturnOnConsecutiveCalls(',', 1);

        $adminAdvancedExportImportController->setModuleTools($moduleTools);

        //act
        $separator = $adminAdvancedExportImportController->getSeparatorForReader();

        //assert
        $this->assertSame(',', $separator);
    }

    public function testGenerateFileToken_Should_Return_File_Token()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());

        //act
        $file_token = $adminAdvancedExportImportController->generateFileToken();
        $length = count(str_split($file_token));

        //assert
        $this->assertSame(40, $length);
    }

    /**
     * @test
     * @covers AdminAdvancedExportImportController::saveImportSettings
     */
    public function AddDefaultSeparatorIfNotCsvFormatAndExportModelChosen()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());

        $aeImport = $this->createPartialMock(self::aeImportClassName, array(
            'copyFromPost', 'save'
        ));
        $aeImport->id_advancedexport = 1;
        $aeImport->file_format = 'xlsx';
        $aeImport->separator = ',';

        //act
        $aeImport = $adminAdvancedExportImportController->saveImportSettings($aeImport);

        //assert
        $this->assertSame(';', $aeImport->separator);
    }

    /**
     * @test
     * @covers AdminAdvancedExportImportController::saveImportSettings
     */
    public function AddUserSeparatorIfCsvFormatChosen()
    {
        //arrange
        $adminAdvancedExportImportController =
            $this->createPartialMock(self::className, array());

        $aeImport = $this->createPartialMock(self::aeImportClassName, array(
            'copyFromPost', 'save'
        ));
        $aeImport->id_advancedexport = 0;
        $aeImport->file_format = 'csv';
        $aeImport->separator = ',';

        //act
        $aeImport = $adminAdvancedExportImportController->saveImportSettings($aeImport);

        //assert
        $this->assertSame(',', $aeImport->separator);
    }
}
