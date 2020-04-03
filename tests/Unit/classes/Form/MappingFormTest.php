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

use MappingForm;
use PHPUnit\Framework\TestCase;

require_once dirname(__file__) . '/../../../../advancedexport.php';
require_once dirname(__file__) . '/../../../../classes/Form/MappingForm.php';

class MappingFormTest extends testcase
{
    const ADVANCED_EXPORT = 'Advancedexport';
    public $mappingForm;

    public function setup()
    {
        $ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mappingForm = new MappingForm($ae);
    }

    private function getMockShort($methods)
    {
        return $this->getmock(self::advanced_export, $methods, [], '', false);
    }

    /**
     * test for displaytemplate
     */
    public function testdisplaytemplate_should_return_template_path()
    {

    }


}
