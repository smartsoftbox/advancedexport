<?php
/**
 * 2016 Smart Soft.
 *
 *  @author    Marcin Kubiak
 *  @copyright Smart Soft
 *  @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use AdvancedExportClass;

require_once dirname(__FILE__).'/../../../advancedexport.php';
require_once dirname(__FILE__) . '/../../../classes/AdvancedExportClass.php';
require_once dirname(__FILE__).'/../../../upgrade/install-4.3.0.php';

class Install430Test extends TestCase
{

    public function setup()
    {

    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfProductsFieldIdIsMoreThen63()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 65)));
        $model['type'] = 'products';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfOrdersFieldIdIsMoreThen66()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 68)));
        $model['type'] = 'orders';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfCategoriesFieldIdIsMoreThen16()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 18)));
        $model['type'] = 'categories';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfManufacturersFieldIdIsMoreThen8()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 10)));
        $model['type'] = 'manufacturers';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfSuppliersFieldIdIsMoreThen7()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 10)));
        $model['type'] = 'suppliers';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfCustomersFieldIdIsMoreThen24()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 27)));
        $model['type'] = 'customers';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_checkIfModelContainsCustomFields_ShouldReturnTrue_IfNewslettersFieldIdIsMoreThen4()
    {
        //arrange
        $model = array();
        $model['fields'] = json_encode(array('fields[]' => array(1, 2, 3, 7)));
        $model['type'] = 'newsletters';

        //act
        $result = checkIfModelContainsCustomFields($model);

        //assert
        $this->assertTrue($result);
    }

    public function test_changeIdsToFieldNames_ShouldChangeIdToFieldName()
    {
        //arrange
        $model = array();

        $model['fields'] = json_encode(array('fields[]' => range(0, 63)));
        $model['type'] = "products";
        $ae = $this->getMockBuilder('AdvancedExport')
            ->disableOriginalConstructor()
            ->getMock();

        $fields = array(
            'products' => $ae->products,
            'orders' => $ae->orders
        );

        //act
        $result = changeIdsToFieldNames($model, $fields);
        $result = json_decode($result, true);

        //assert
        $this->assertSame($result['fields[]'][0], 'id_product');

    }

    public function test_changeIdsToFieldNames_ShouldNotRemoveOtherOptions()
    {
        //arrange
        $model = array();

        $model['fields'] = json_encode(array('fields[]' => range(0, 63), 'out_of_stoc' => '1'));
        $model['type'] = "products";
        $ae = $this->getMockBuilder('AdvancedExport')
            ->disableOriginalConstructor()
            ->getMock();

        $fields = array(
            'products' => $ae->products,
            'orders' => $ae->orders
        );

        //act
        $result = changeIdsToFieldNames($model, $fields);
        $result = json_decode($result, true);

        //assert
        $this->assertSame($result['out_of_stoc'], '1');

    }

    public function teardown()
    {
    }
}
