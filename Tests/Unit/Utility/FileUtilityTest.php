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
    public function calculateDimensions(array $expected, array $configuration)
    {
        $fileMock = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', ['getProperty'], [], '', false);

        $fileMock
            ->expects($this->at(0))
            ->method('getProperty')
            ->with('width')
            ->will($this->returnValue(400));

        $fileMock
            ->expects($this->at(1))
            ->method('getProperty')
            ->with('height')
            ->will($this->returnValue(300));

        $this->assertEquals($expected, FileUtility::calculateDimensions($fileMock, $configuration));
    }

    public function calculateDimensionsDataProvider()
    {
        return [
            [['width' => 400, 'height' => 300], []],
            [['width' => 100, 'height' => 75], ['width' => "100"]],
            [['width' => 134, 'height' => 100], ['height' => "100"]],
            [['width' => 200, 'height' => 200], ['width' => "200", 'height' => "200"]],
            [['width' => 200, 'height' => 200], ['width' => "200c", 'height' => "200"]],
            [['width' => 200, 'height' => 200], ['width' => "200", 'height' => "200c"]],
            [['width' => 200, 'height' => 200], ['width' => "200c", 'height' => "200c"]],
            [['width' => 100, 'height' => 75], ['maxWidth' => "100"]],
            [['width' => 134, 'height' => 100], ['maxHeight' => "100"]],
            [['width' => 100, 'height' => 75], ['maxWidth' => "100", 'maxHeight' => "100"]],
            [['width' => 400, 'height' => 300], ['minWidth' => "100"]],
            [['width' => 400, 'height' => 300], ['minHeight' => "100"]],
            [['width' => 400, 'height' => 300], ['minWidth' => "100", 'minHeight' => "100"]],
            [['width' => 267, 'height' => 200], ['minWidth' => "200", 'minHeight' => "200", 'maxWidth' => "100", 'maxHeight' => "100"]],
            [['width' => 100, 'height' => 75], ['width' => "200", 'maxWidth' => "100", 'maxHeight' => "100"]],
            [['width' => 100, 'height' => 75], ['width' => "200", 'height' => "200", 'maxWidth' => "100", 'maxHeight' => "100"]],
            [['width' => 100, 'height' => 100], ['width' => "200c", 'height' => "200c", 'maxWidth' => "100", 'maxHeight' => "100"]],
            [['width' => 200, 'height' => 150], ['width' => "200", 'minWidth' => "100", 'minHeight' => "100"]],
            [['width' => 200, 'height' => 200], ['width' => "200", 'height' => "200", 'minWidth' => "100", 'minHeight' => "100"]],
            [['width' => 200, 'height' => 200], ['width' => "200c", 'height' => "200c", 'minWidth' => "100", 'minHeight' => "100"]],
            [['width' => 500, 'height' => 375], ['width' => "500"]],
            [['width' => 667, 'height' => 500], ['height' => "500"]],
            [['width' => 500, 'height' => 500], ['width' => "500", 'height' => "500"]],
            [['width' => 500, 'height' => 500], ['width' => "500c", 'height' => "500"]],
            [['width' => 500, 'height' => 500], ['width' => "500", 'height' => "500c"]],
            [['width' => 500, 'height' => 500], ['width' => "500c", 'height' => "500c"]],
            [['width' => 400, 'height' => 300], ['maxWidth' => "500"]],
            [['width' => 400, 'height' => 300], ['maxHeight' => "500"]],
            [['width' => 400, 'height' => 300], ['maxWidth' => "500", 'maxHeight' => "500"]],
            [['width' => 400, 'height' => 300], ['minWidth' => "500"]],
            [['width' => 400, 'height' => 300], ['minHeight' => "500"]],
            [['width' => 400, 'height' => 300], ['minWidth' => "500", 'minHeight' => "500"]],
        ];
    }
}
