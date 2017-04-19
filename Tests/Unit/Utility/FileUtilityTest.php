<?php

namespace R3H6\ImageAsyncProcess\Tests\Utility;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 3 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

use R3H6\ImageAsyncProcess\Utility\FileUtility;

/**
 * Unit test for the ErrorPageController.
 */
class FileUtilityTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @test
     * @dataProvider calculateDimensionsDataProvider
     */
    public function calculateDimensions($file, $configuration, $expected)
    {
        $this->assertEquals($expected, FileUtility::calculateDimensions($file, $configuration));
    }

    public function calculateDimensionsDataProvider()
    {
        $file = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', ['getProperty'], [], '', false);
        // $file
        //     ->expects($this->any())
        //     ->method('getProperty')
        //     ->with($this->equalTo('width'))
        //     ->will($this->returnValue(1200));
        // $file
        //     ->expects($this->any())
        //     ->method('getProperty')
        //     ->with($this->equalTo('width'))
        //     ->will($this->returnValue(1200));

        return [
            [$file, ['width' => '200', 'height' => '100'], ['width' => 200, 'height' => 100]]
        ];
    }
}
