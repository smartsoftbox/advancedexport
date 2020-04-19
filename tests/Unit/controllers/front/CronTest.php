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

use AdvancedexportCronModuleFrontController;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../../../../controllers/front/cron.php';
require_once dirname(__FILE__) . '/../../../../classes/ModuleTools.php';
require_once dirname(__FILE__) . '/../../../../advancedexport.php';

class AdvancedexportCronModuleFrontControllerTest extends testcase
{
    const className = 'AdvancedexportCronModuleFrontController';
    const module = 'Advancedexport';

    public function testIsTimeForRun_Should_Return_False_When_Cron_Time_Every_1am()
    {
        //arrange
        $advancedexportCronModuleFrontController = $this->createPartialMock(self::className, array());

        $cron = array();
        $cron['cron_hour'] = '1';
        $cron['cron_day'] = '*';
        $cron['cron_month'] = '*';
        $cron['cron_week'] = '*';

        //act
        $isTimeForRun = $advancedexportCronModuleFrontController->isTimeForRun($cron);

        //assert
        $this->assertFalse($isTimeForRun);
    }

    public function testIsTimeForRun_Should_Return_True_When_Cron_Time_Every_Hour()
    {
        //arrange
        $advancedexportCronModuleFrontController = $this->createPartialMock(self::className, array());

        $cron = array();
        $cron['cron_hour'] = date('H');
        $cron['cron_day'] = '*';
        $cron['cron_month'] = '*';
        $cron['cron_week'] = '*';

        //act
        $isTimeForRun = $advancedexportCronModuleFrontController->isTimeForRun($cron);

        //assert
        $this->assertTrue($isTimeForRun);
    }

    public function testIsTimeForRun_Should_Return_True_When_Cron_Time_Every_DayWeek()
    {
        //arrange
        $advancedexportCronModuleFrontController = $this->createPartialMock(self::className, array());
        $today = date("Y-m-d");
        $number = date('N', strtotime($today));

        $cron = array();
        $cron['cron_hour'] = date('H');
        $cron['cron_day'] = '*';
        $cron['cron_month'] = '*';
        $cron['cron_week'] = $number;

        //act
        $isTimeForRun = $advancedexportCronModuleFrontController->isTimeForRun($cron);

        //assert
        $this->assertTrue($isTimeForRun);
    }

    public function testRunCronTask_Should_Run_cronImportTask_When_Is_Import_True()
    {
        //arrange
        $task = array();
        $task['is_import'] = true;
        $task['id_model'] = 1;

        $advancedexportCronModuleFrontController = $this->createPartialMock(self::className, array(
            'isTimeForRun', 'updateLastExport'
        ));
        $advancedexportCronModuleFrontController->expects($this->once())
            ->method('isTimeForRun')
            ->with($this->equalTo($task))
            ->willReturn(true);

        $advancedexportCronModuleFrontController->expects($this->once())
            ->method('updateLastExport')
            ->with($this->equalTo($task))
            ->willReturn(true);

        $module = $this->createPartialMock(self::module, array(
            'cronImportTask', 'cronExportTask'
        ));

        $module->expects($this->once())
            ->method('cronImportTask')
            ->with($this->equalTo($task['id_model']))
            ->willReturn(true);

        $module->expects($this->never())
            ->method('cronExportTask');

        $advancedexportCronModuleFrontController->module = $module;

        //act
        $advancedexportCronModuleFrontController->runCronTask($task);
    }

    public function testRunCronTask_Should_Run_cronExportTask_When_Is_Import_False()
    {
        //arrange
        $task = array();
        $task['is_import'] = false;
        $task['id_model'] = 1;

        $advancedexportCronModuleFrontController = $this->createPartialMock(self::className, array(
            'isTimeForRun', 'updateLastExport'
        ));
        $advancedexportCronModuleFrontController->expects($this->once())
            ->method('isTimeForRun')
            ->with($this->equalTo($task))
            ->willReturn(true);

        $advancedexportCronModuleFrontController->expects($this->once())
            ->method('updateLastExport')
            ->with($this->equalTo($task))
            ->willReturn(true);

        $module = $this->createPartialMock(self::module, array(
            'cronImportTask', 'cronExportTask'
        ));

        $module->expects($this->never())
            ->method('cronImportTask');

        $module->expects($this->once())
            ->method('cronExportTask')
            ->with($this->equalTo($task['id_model']))
            ->willReturn(true);

        $advancedexportCronModuleFrontController->module = $module;

        //act
        $advancedexportCronModuleFrontController->runCronTask($task);
    }
}
