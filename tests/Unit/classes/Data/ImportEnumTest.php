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

use ImportEnum;
use PHPUnit\Framework\TestCase;
use PrestaShopException;

require_once dirname(__file__) . '/../../../../classes/Data/ImportEnum.php';

class ImportEnumTest extends testcase
{
    /**
     * Test for getFields
     */
    public function testGetFields_Should_Return_Import_Enum_When_Alaways()
    {
        //arrange
        $expected = array(
            array(
                'entity' => 'categories',
                'delete' => true,
                'skip' => true,
                'force' => true,
                'isMultiLang' => true,
                'id' => 0
            ),
            array(
                'entity' => 'products',
                'delete' => true,
                'skip' => true,
                'force' => true,
                'isMultiLang' => true,
                'id' => 1
            ),
            array(
                'entity' => 'combinations',
                'delete' => true,
                'isMultiLang' => false,
                'id' => 2
            ),
            array(
                'entity' => 'customers',
                'delete' => true,
                'force' => true,
                'isMultiLang' => false,
                'id' => 3
            ),
            array(
                'entity' => 'addresses',
                'delete' => true,
                'force' => true,
                'isMultiLang' => false,
                'id' => 4
            ),
            array(
                'entity' => 'brands',
                'delete' => true,
                'skip' => true,
                'force' => true,
                'isMultiLang' => false,
                'id' => 5
            ),
            array(
                'entity' => 'suppliers',
                'delete' => true,
                'skip' => true,
                'force' => true,
                'isMultiLang' => false,
                'id' => 6
            ),
        );

        //act
        $importEnum = new ImportEnum();
        $import_enum = $importEnum->getFields();

        //assert
        $this->assertEquals($expected, $import_enum);
    }


    /**
     * Test for getEntityById
     */
    public function testGetEntityById_Should_Entity_Name_Categories_When_Id_Is_0()
    {
        //act
        $type = ImportEnum::getEntityById(0);

        //assert
        $this->assertSame('categories', $type);
    }

    /**
     * Test for GetImportFromName
     */
    public function testGetImportFromName_Should_Throw_Exception_When_Id_Is_Null()
    {
        //arrange
        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid import from id');

        //act
        ImportEnum::getEntityById(null);
    }

    /**
     * Test for GetImportFromName
     */
    public function testGetImportFromName_Should_Throw_Exception_When_Id_Is_3()
    {
        //arrange
        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid import from id');

        //act
        ImportEnum::getEntityById(9);
    }
}
