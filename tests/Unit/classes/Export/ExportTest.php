<?php
/**
 * 2020 smart soft.
 *
 * @author    marcin kubiak
 * @copyright smart soft
 * @license   commercial license
 *  international registered trademark & property of smart soft
 */

namespace Tests\Unit;

use Export;
use phpDocumentor\Reflection\Types\Object_;
use Product;
use AdvancedExportClass;
use PHPUnit\Framework\TestCase;

require_once dirname(__file__) . '/../../../../classes/Export/Export.php';
require_once dirname(__file__) . '/../../../../classes/Model/AdvancedExportClass.php';


class ExportTest extends testcase
{
    const className = 'Export';
    public $ob;

    public function setup()
    {
        $ae = $this->getMockBuilder(self::className)
            ->disableOriginalConstructor()
            ->getMock();
        $this->ob = new Export();
    }

    private function getMockShort($methods)
    {
        return $this->getmock(self::advanced_export, $methods, [], '', false);
    }

    /**
     * Test for sortSqlFields
     */
    public function testSortSqlFields_Should_Not_Add_Other_Fields_When_Always()
    {
        //arrange
        $field = 'test';

        $allFields = array(
            'test' => array(
                'name' => 'test',
                'field' => 'test',
                'table' => 'other',
                'attribute' => false
            )
        );
        $sorted_fields = array(
            'sqlfields' => array(
                array(
                    'name' => 'test2',
                    'table' => 'products',
                    'attribute' => false
                )
            )
        );

        //act
        $this->ob->sortSqlFields($allFields, $field, $sorted_fields);
        $sorted_fields_number = count($sorted_fields['sqlfields']);

        //assert
        $this->assertSame(1, $sorted_fields_number);
    }

    /**
     * Test for sortSqlFields
     */
    public function testSortSqlFields_Should_Add_Not_Other_Static_Fields_When_Always()
    {
        //arrange
        $field = 'test';

        $allFields = array(
            'test' => array(
                'name' => 'test',
                'field' => 'test',
                'table' => 'products',
                'attribute' => false
            )
        );
        $sorted_fields = array(
            'sqlfields' => array(
                array(
                    'name' => 'test2',
                    'table' => 'products',
                    'attribute' => false
                )
            )
        );

        //act
        $this->ob->sortSqlFields($allFields, $field, $sorted_fields);
        $sorted_fields_number = count($sorted_fields['sqlfields']);

        //assert
        $this->assertSame(2, $sorted_fields_number);
        $this->assertSame('`test`', $sorted_fields['sqlfields'][1]);
    }

    /**
     * Test for sortSqlFields
     */
    public function testSortSqlFields_Should_Not_Add_Static_Fields_When_Always()
    {
        //arrange
        $field = 'test';

        $allFields = array(
            'test' => array(
                'name' => 'test',
                'table' => 'static',
                'attribute' => false
            )
        );
        $sorted_fields = array(
            'sqlfields' => array(
                array(
                    'name' => 'test2',
                    'table' => 'products',
                    'attribute' => false
                )
            )
        );

        //act
        $this->ob->sortSqlFields($allFields, $field, $sorted_fields);
        $sorted_fields_number = count($sorted_fields['sqlfields']);

        //assert
        $this->assertSame(1, $sorted_fields_number);
    }

    /**
     * Test for sortSqlFields
     */
    public function testSortSqlFields_Should_Add_As_To_Fields_When_As_Is_True()
    {
        //arrange
        $field = 'test';

        $allFields = array(
            'test' => array(
                'name' => 'test',
                'field' => 'test_name',
                'table' => 'products',
                'as' => true,
                'attribute' => false
            )
        );
        $sorted_fields = array(
            'sqlfields' => array(
                array(
                    'name' => 'test2',
                    'table' => 'product',
                    'attribute' => false
                )
            )
        );

        //act
        $this->ob->sortSqlFields($allFields, $field, $sorted_fields);
        $sorted_fields_number = count($sorted_fields['sqlfields']);

        //assert
        $this->assertSame(2, $sorted_fields_number);
        $this->assertSame('`name` as test_name', $sorted_fields['sqlfields'][1]);
    }

    /**
     * Test for sortSqlFields
     */
    public function testSortSqlFields_Should_Add_Alias_To_Fields_When_Alias_Is_Not_Empty()
    {
        //arrange
        $field = 'test';

        $allFields = array(
            'test' => array(
                'name' => 'test',
                'field' => 'test',
                'table' => 'products',
                'alias' => 'p',
                'attribute' => false
            )
        );
        $sorted_fields = array(
            'sqlfields' => array(
                array(
                    'name' => 'test2',
                    'table' => 'product',
                    'attribute' => false
                )
            )
        );

        //act
        $this->ob->sortSqlFields($allFields, $field, $sorted_fields);
        $sorted_fields_number = count($sorted_fields['sqlfields']);

        //assert
        $this->assertSame('p.`test`', $sorted_fields['sqlfields'][1]);
    }

    /**
     * Test for sortSqlFields
     */
    public function testSortSqlFields_Should_Add_As_And_Alias_To_Fields_When_Not_Empty_Both()
    {
        //arrange
        $field = 'test';

        $allFields = array(
            'test' => array(
                'name' => 'test',
                'field' => 'test_name',
                'table' => 'products',
                'attribute' => false,
                'as' => true,
                'alias' => 'p'
            )
        );
        $sorted_fields = array(
            'sqlfields' => array(
                array(
                    'name' => 'test2',
                    'table' => 'product',
                    'attribute' => false
                )
            )
        );

        //act
        $this->ob->sortSqlFields($allFields, $field, $sorted_fields);
        $sorted_fields_number = count($sorted_fields['sqlfields']);

        //assert
        $this->assertSame(2, $sorted_fields_number);
        $this->assertSame('p.`name` as test_name', $sorted_fields['sqlfields'][1]);
    }

    public function testFputToFile_Should_Throw_Exception_If_File_AllExportFields_Object_AE_Is_Empty()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());
        $product = $this->createPartialMock('Product', array());

        //assert
        $this->expectException('PrestaShopException');
        $this->expectExceptionMessage('Invalid argument');

        //act
        $export->fputToFile(null, array(), $product, new \AdvancedExportClass());
    }

    public function testReplaceDecimalSeparator_Should_Not_Change_Decimal_Separator_When_Decimal_Separator_Is_Minus_one()
    {
        //arrange
        $object = array();
        $object['price'] = 12.55;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_round = -1;

        //act
        $export->roundValues($object, $ae, 'price');

        //assert
        $this->assertSame(12.55, $object['price']);
    }

    public function testReplaceDecimalSeparator_Should_Not_Change_Round_When_Decimal_Round_Is_Minus_one_String()
    {
        //arrange
        $object = array();
        $object['price'] = 12.55;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_separator = '-1';

        //act
        $export->replaceDecimalSeparator($object, $ae, 'price');

        //assert
        $this->assertSame(12.55, $object['price']);
    }

    public function testReplaceDecimalSeparator_Should_Change_Round_When_Decimal_Round_Is_1()
    {
        //arrange
        $object = array();
        $object['price'] = 12.55;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_separator = ',';

        //act
        $export->replaceDecimalSeparator($object, $ae, 'price');

        //assert
        $this->assertSame('12,55', $object['price']);
    }

    public function testReplaceDecimalSeparator_Should_Change_Round_When_Decimal_Round_Is_0()
    {
        //arrange
        $object = array();
        $object['price'] = 12.5;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_separator = '.';

        //act
        $export->replaceDecimalSeparator($object, $ae, 'price');

        //assert
        $this->assertSame(12.5, (float)$object['price']);
    }

    public function testReplaceDecimalSeparator_Should_Change_Round_When_Decimal_Round_Is_1_string()
    {
        //arrange
        $object = array();
        $object['price'] = 12.5;

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_separator = ',';

        //act
        $export->replaceDecimalSeparator($object, $ae, 'price');

        //assert
        $this->assertSame('12,5', $object['price']);
    }

    public function testReplaceDecimalSeparator_Should_Change_Round_When_Decimal_Round_Is_1_String_And_Price_Is_String()
    {
        //arrange
        $object = array();
        $object['price'] = '12.5';

        $export = $this->createPartialMock(self::className, array());

        $ae = $this->createPartialMock('AdvancedExportClass', array());
        $ae->decimal_separator = '.';

        //act
        $export->replaceDecimalSeparator($object, $ae, 'price');

        //assert
        $this->assertSame('12.5', $object['price']);
    }

    public function testIsDecimal_Should_Numbers_Start_With_Zero_Should_Not_Be_Decimal()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->isDecimal(01);

        //assert
        $this->assertFalse($is_numeric);
    }

    public function testIsDecimal_Should_Numbers_Start_With_Zero_And_Is_String_Should_Not_Be_Decimal()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->isDecimal('01');

        //assert
        $this->assertFalse($is_numeric);
    }

    public function testIsDecimal_Should_Numbers_Start_With_Two_Zero_Should_Not_Be_Decimal()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->isDecimal(001);

        //assert
        $this->assertFalse($is_numeric);
    }

    public function testCastValues_Should_Cast_Integer_As_Integer()
    {
        //arrange
        $object = array();
        $object['price'] = 7;

        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->castValues($object, 'price');

        //assert
        $this->assertInternalType('int', $object['price']);
    }

    public function testCastValues_Should_Cast_Integer_String_As_Integer()
    {
        //arrange
        $object = array();
        $object['price'] = '7';

        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->castValues($object, 'price');

        //assert
        $this->assertInternalType('int', $object['price']);
    }


    public function testCastValues_Should_Cast_Float_As_Float()
    {
        //arrange
        $object = array();
        $object['price'] = 7.5;

        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->castValues($object, 'price');

        //assert
        $this->assertInternalType('float', $object['price']);
    }

    public function testCastValues_Should_Cast_Float_String_As_Float()
    {
        //arrange
        $object = array();
        $object['price'] = '7.5';

        $export = $this->createPartialMock(self::className, array());

        //act
        $is_numeric = $export->castValues($object, 'price');

        //assert
        $this->assertInternalType('float', $object['price']);
    }

    public function testGetFileUrl_Should_Return_Filename_As_Date_When_Filename_Is_Null()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $filename = $export->getFileUrl(null, 'products', 'csv');

        //assert
        $this->assertContains('/modules/advancedexport/csv/products/products2020', $filename);
    }

    public function testGetFileUrl_Should_Return_Filename_As_Date_When_Filename_Is_Empty()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $filename = $export->getFileUrl('', 'products', 'csv');

        //assert
        $this->assertContains('/modules/advancedexport/csv/products/products2020', $filename);
    }

    public function testGetFileUrl_Should_Return_Filename_As_Date_When_Filename_Is_Empty_And_File_Format_Is_Not_Empty()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $filename = $export->getFileUrl('', 'products', 'xls');

        //assert
        $this->assertContains('/modules/advancedexport/csv/products/products2020', $filename);
        $this->assertContains('.xls', $filename);
    }

    public function testGetFileUrl_Should_Return_Filename_When_Filename_Is_Not_Empty()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $filename = $export->getFileUrl('test', 'products', 'csv');

        //assert
        $this->assertContains('/modules/advancedexport/csv/products/test.csv', $filename);
    }

    public function testGetFileUrl_Should_Return_Filename_When_Filename_Is_Not_Empty_And_File_Format_Is_Not_Empty()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $filename = $export->getFileUrl('test', 'products', 'xls');

        //assert
        $this->assertContains('/modules/advancedexport/csv/products/test.xls', $filename);
    }

    public function testGetId_Should_Return_Id_Category_When_Type_Is_Categories()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('categories');

        //assert
        $this->assertSame('id_category', $id);
    }

    public function testGetId_Should_Return_Id_Customer_When_Type_Is_Customers()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('customers');

        //assert
        $this->assertSame('id_customer', $id);
    }

    public function testGetId_Should_Return_Id_Manufacturer_When_Type_Is_Manufacturers()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('manufacturers');

        //assert
        $this->assertSame('id_manufacturer', $id);
    }

    public function testGetId_Should_Return_Id_When_Type_Is_Newsletters()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('newsletters');

        //assert
        $this->assertSame('id', $id);
    }

    public function testGetId_Should_Return_Id_Order_When_Type_Is_Orders()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('orders');

        //assert
        $this->assertSame('id_order', $id);
    }

    public function testGetId_Should_Return_Id_Product_When_Type_Is_Products()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('products');

        //assert
        $this->assertSame('id_product', $id);
    }

    public function testGetId_Should_Return_Id_Supplier_When_Type_Is_Suppliers()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('suppliers');

        //assert
        $this->assertSame('id_supplier', $id);
    }

    public function testGetId_Should_Return_Id_Address_When_Type_Is_Addresses()
    {
        //arrange
        $export = $this->createPartialMock(self::className, array());

        //act
        $id = $export->getId('addresses');

        //assert
        $this->assertSame('id_address', $id);
    }

    public function testPreperFieldsForExport_Should_Not_Remove_First_Zero_From_Example_Reference()
    {
        //arrange
        $allexportfields = array('reference');
        $object = array('reference' => '01234');
        $ae = new AdvancedExportClass();
        $ae->file_format = 'csv';
        $ae->charset = 'UTF-8';

        $export = $this->createPartialMock(self::className, array(
            'processDecimalSettings',
            'castValues'
        ));

        $export->expects($this->never())
            ->method('processDecimalSettings')
            ->willReturn($this->anything());

        $export->expects($this->never())
            ->method('castValues')
            ->willReturn($this->anything());

        //act
        $readyForExport = $export->preperFieldsForExport($allexportfields, $object, $ae);

        //assert
        $this->assertSame($readyForExport['reference'], $object['reference']);
    }

    public function testPreperFieldsForExport_Should_Not_Remove_First_Two_Zeros_From_Example_Reference()
    {
        //arrange
        $allexportfields = array('reference');
        $object = array('reference' => '001234');
        $ae = new AdvancedExportClass();
        $ae->file_format = 'csv';
        $ae->charset = 'UTF-8';

        $export = $this->createPartialMock(self::className, array(
            'processDecimalSettings',
            'castValues'
        ));

        $export->expects($this->never())
            ->method('processDecimalSettings')
            ->willReturn($this->anything());

        $export->expects($this->never())
            ->method('castValues')
            ->willReturn($this->anything());

        //act
        $readyForExport = $export->preperFieldsForExport($allexportfields, $object, $ae);

        //assert
        $this->assertSame($readyForExport['reference'], $object['reference']);
    }
}
