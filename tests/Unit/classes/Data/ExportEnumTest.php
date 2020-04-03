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

use ExportEnum;
use PHPUnit\Framework\TestCase;

require_once dirname(__file__) . '/../../../../classes/Data/ExportEnum.php';

class ExportEnumTest extends testcase
{
    /**
     * Test for getSaveType
     */
    public function testExportEnum_Values()
    {
        //arrange
        $export_entities = array(
            'products' => 'product',
            'orders' => 'order',
            'categories' => 'category',
            'manufacturers' => 'manufacturer',
            'newsletters' => '',
            'suppliers' => 'supplier',
            'customers' => 'customer',
            'addresses' => 'address'
        );

        //assert
        $this->assertSame(
            $export_entities, ExportEnum::$export_entities
        );
    }

    public function testGetExportEntities_Should_Return_Only_Entities()
    {
        //arrange
        $only_entities = array(
            'products',
            'orders',
            'categories',
            'manufacturers',
            'newsletters',
            'suppliers',
            'customers',
            'addresses'
        );

        //act
        $entities = ExportEnum::getExportEntities();

        //assert
        $this->assertSame(
            $only_entities, $entities
        );
    }

    public function testGetObjectByEntityName_Should_Return_Object_Name_Product_When_Entity_Is_Products()
    {
        //act
        $object = ExportEnum::getObjectByEntityName('products');

        //assert
        $this->assertSame(
            'product', $object
        );
    }

    public function testGetObjectByEntityName_Should_Throw_Exception_When_Entity_Is_Not_Defined()
    {
        //assert
        $this->expectExceptionMessage('Invalid export entity.');

        //act
        $object = ExportEnum::getObjectByEntityName('tests');
    }
}
