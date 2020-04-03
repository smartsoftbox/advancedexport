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

use ImportForm;
use PHPUnit\Framework\TestCase;

require_once dirname(__file__) . '/../../../../advancedexport.php';
require_once dirname(__file__) . '/../../../../classes/Form/ImportForm.php';

class ImportFormTest extends testcase
{
    const ADVANCED_EXPORT = 'Advancedexport';
    public $importForm;

    public function setup()
    {
        $ae = $this->getMockBuilder(self::ADVANCED_EXPORT)
            ->disableOriginalConstructor()
            ->getMock();
        $this->importForm = new ImportForm($ae);
    }

    private function getMockShort($methods)
    {
        return $this->getmock(self::advanced_export, $methods, [], '', false);
    }

    /**
     * test for displayTemplate
     */
    public function testDisplayTemplate_should_return_template_path()
    {

    }


}
