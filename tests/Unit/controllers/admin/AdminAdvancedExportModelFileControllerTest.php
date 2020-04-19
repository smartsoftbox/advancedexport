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
require_once dirname(__FILE__) . '/../../../../controllers/admin/AdminAdvancedExportModelFileController.php';
require_once dirname(__FILE__) . '/../../../../classes/Model/AdvancedExportClass.php';

class AdminAdvancedExportModelFileControllerTest extends testcase
{
    const className = 'AdminAdvancedExportModelFileController';
    const moduleTools = 'ModuleTools';
}
