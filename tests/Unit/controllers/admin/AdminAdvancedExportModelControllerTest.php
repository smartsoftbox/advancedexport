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
require_once dirname(__FILE__) . '/../../../../controllers/admin/AdminAdvancedExportModelController.php';

class AdminAdvancedExportModelControllerTest extends testcase
{
    const className = 'AdminAdvancedExportModelController';

    public function testGetExportModelFormSpecificFields_Should_Return_Products_Form_Fields_When_Type_Is_Products()
    {
        //arrange
        $productForm = $this->createPartialMock('ProductsForm', array('formFields'));
        $productForm->expects($this->once())
            ->method('formFields')
            ->will($this->returnValue('products'));

        $controller = $this->createPartialMock(self::className, array('getExportModelFormObject'));
        $controller->expects($this->once())->method('getExportModelFormObject')
            ->with('ProductsForm')
            ->will($this->returnValue($productForm));

        //act
        $form_fields = $controller->getExportModelFormSpecificFields('products');

        //assert
        $this->assertSame('products', $form_fields);
    }


}
