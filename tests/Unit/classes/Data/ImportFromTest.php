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

use ImportFrom;
use PHPUnit\Framework\TestCase;
use PrestaShopException;

require_once dirname(__file__) . '/../../../../classes/Data/ImportFrom.php';

class ImportFromTest extends testcase
{
    /**
     * Test for getImportFrom
     */
    public function testGetImportFrom_Should_Return_ImportFrom_Array_When_Always()
    {
        //arrange
        $expected = array(
            array('id' => 0, 'name' => 'model', 'public_name' => 'Export Model'),
            array('id' => 1, 'name' => 'upload', 'public_name' => 'Upload'),
            array('id' => 2, 'name' => 'url', 'public_name' => 'Url'),
            array('id' => 3, 'name' => 'ftp', 'public_name' => 'Ftp'),
            array('id' => 4, 'name' => 'sftp', 'public_name' => 'SFtp'),
        );

        //act
        $import_from = ImportFrom::getImportFrom();

        //assert
        $this->assertEquals($expected, $import_from);
    }

    /**
     * Test for getImportFromPublicName
     */
    public function testGetImportFromPublicName_Should_Public_Name_Export_Model_When_Id_Is_0()
    {
        //arrange
        $expected = 'Export Model';

        //act
        $public_name = ImportFrom::getImportFromPublicName(0);

        //assert
        $this->assertSame($expected, $public_name);
    }


    /**
     * Test for GetImportFromPublicName
     */
    public function testGetImportFromPublicName_Should_Throw_Exception_When_Id_Is_Null()
    {
        //arrange
        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid import from id');

        //act
        ImportFrom::getImportFromPublicName(null);
    }

    /**
     * Test for GetImportFromPublicName
     */
    public function testGetImportFromPublicName_Should_Throw_Exception_When_Id_Is_5()
    {
        //arrange
        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid import from id');

        //act
        ImportFrom::getImportFromPublicName(5);
    }

    /**
     * Test for getImportFromName
     */
    public function testGetImportFromPublicName_Should_Public_Export_Model_When_Id_Is_0()
    {
        //arrange
        $expected = 'model';

        //act
        $public_name = ImportFrom::getImportFromName(0);

        //assert
        $this->assertSame($expected, $public_name);
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
        ImportFrom::getImportFromName(null);
    }

    /**
     * Test for GetImportFromName
     */
    public function testGetImportFromName_Should_Throw_Exception_When_Id_Is_5()
    {
        //arrange
        $this->expectException(PrestaShopException::class);
        $this->expectExceptionMessage('Invalid import from id');

        //act
        ImportFrom::getImportFromName(5);
    }
}
