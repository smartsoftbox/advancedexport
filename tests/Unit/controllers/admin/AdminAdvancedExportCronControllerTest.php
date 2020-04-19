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

use AdminAdvancedExportCronController;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../../../../controllers/admin/AdminAdvancedExportCronController.php';
require_once dirname(__FILE__) . '/../../../../classes/ModuleTools.php';


class AdminAdvancedExportCronControllerTest extends testcase
{
    const className = 'AdminAdvancedExportCronController';
    const moduleTools = 'ModuleTools';

    public function testGetAll_Should_Not_Add_Import_Models_When_PrestaShop_Is_Lower_Then_17()
    {
        //arrange
        $adminAdvancedExportCronController = $this->createPartialMock(self::className, array(
            'getExportModels', 'getImportModels'
        ));

        $adminAdvancedExportCronController->expects($this->once())
            ->method('getExportModels');

        $adminAdvancedExportCronController->expects($this->once())
            ->method('getImportModels');

        $moduleTools = $this->createPartialMock(self::moduleTools, array());
        $adminAdvancedExportCronController->setModuleTools($moduleTools);

        //act
        $all_models = $adminAdvancedExportCronController->getAll();

        //assert
        $this->assertTrue(isset($all_models['import']));
    }
}
