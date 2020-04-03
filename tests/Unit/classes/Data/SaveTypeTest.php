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

use PHPUnit\Framework\TestCase;
use SaveType;

require_once dirname(__file__) . '/../../../../classes/Data/SaveType.php';

class SaveTypeTest extends testcase
{
    /**
     * Test for getSaveType
     */
    public function testGetSaveType_Should_Return_Correct_Data_When_Always()
    {
        //act
        $save_type = SaveType::getSaveTypes();
        $expected = array(
            array('id' => 0, 'name' => 'Save to disc', 'short_name' => 'disc'),
            array('id' => 1, 'name' => 'Ftp', 'short_name' => 'ftp'),
            array('id' => 2, 'name' => 'Sent to email', 'short_name' => 'email'),
            array('id' => 3, 'name' => 'SFtp', 'short_name' => 'sftp')
        );

        //assert
        $this->assertEquals($expected, $save_type);
    }
}
