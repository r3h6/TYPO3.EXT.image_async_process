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
    public function calculateDimensions($fileWidth, $fileHeight, array $expected, array $configuration)
    {
        $fileMock = $this->getMock('TYPO3\\CMS\\Core\\Resource\\File', ['getProperty'], [], '', false);

        $fileMock
            ->expects($this->at(0))
            ->method('getProperty')
            ->with('width')
            ->will($this->returnValue($fileWidth));

        $fileMock
            ->expects($this->at(1))
            ->method('getProperty')
            ->with('height')
            ->will($this->returnValue($fileHeight));

        $this->assertEquals($expected, FileUtility::calculateDimensions($fileMock, $configuration));
    }

    public function calculateDimensionsDataProvider()
    {
        return [
            [400, 300, ['width' => 400, 'height' => 300], []],
            [400, 300, ['width' => 100, 'height' => 75], ['width' => "100"]],
            [400, 300, ['width' => 134, 'height' => 100], ['height' => "100"]],
            [400, 300, ['width' => 400, 'height' => 300], ['minWidth' => "100"]],
            [400, 300, ['width' => 400, 'height' => 300], ['minHeight' => "100"]],
            [400, 300, ['width' => 100, 'height' => 75], ['maxWidth' => "100"]],
            [400, 300, ['width' => 134, 'height' => 100], ['maxHeight' => "100"]],
            [400, 300, ['width' => 100, 'height' => 100], ['width' => "100", 'height' => "100"]],
            [400, 300, ['width' => 400, 'height' => 300], ['minWidth' => "100", 'minHeight' => "100"]],
            [400, 300, ['width' => 100, 'height' => 75], ['maxWidth' => "100", 'maxHeight' => "100"]],
            [400, 300, ['width' => 500, 'height' => 375], ['width' => "500"]],
            [400, 300, ['width' => 667, 'height' => 500], ['height' => "500"]],
            [400, 300, ['width' => 400, 'height' => 300], ['minWidth' => "500"]],
            [400, 300, ['width' => 400, 'height' => 300], ['minHeight' => "500"]],
            [400, 300, ['width' => 400, 'height' => 300], ['maxWidth' => "500"]],
            [400, 300, ['width' => 400, 'height' => 300], ['maxHeight' => "500"]],
            [400, 300, ['width' => 500, 'height' => 500], ['width' => "500", 'height' => "500"]],
            [400, 300, ['width' => 400, 'height' => 300], ['minWidth' => "500", 'minHeight' => "500"]],
            [400, 300, ['width' => 400, 'height' => 300], ['maxWidth' => "500", 'maxHeight' => "500"]],
            [400, 300, ['width' => 500, 'height' => 375], ['width' => "500", 'minWidth' => "100"]],
            [400, 300, ['width' => 100, 'height' => 100], ['width' => "100", 'minHeight' => "100"]],
            [400, 300, ['width' => 100, 'height' => 100], ['width' => "100", 'minWidth' => "100", 'minHeight' => "100"]],
            [400, 300, ['width' => 200, 'height' => 150], ['width' => "100", 'minWidth' => "200", 'maxHeight' => "100"]],
            [400, 300, ['width' => 667, 'height' => 500], ['height' => "500", 'minHeight' => "100"]],
            [400, 300, ['width' => 134, 'height' => 100], ['height' => "100", 'minWidth' => "100"]],
            [400, 300, ['width' => 134, 'height' => 100], ['height' => "100", 'minWidth' => "100", 'minHeight' => "100"]],
            [400, 300, ['width' => 100, 'height' => 200], ['width' => "100", 'height' => "100", 'minHeight' => "200"]],
            [400, 300, ['width' => 500, 'height' => 375], ['minWidth' => "500", 'maxWidth' => "100"]],
            [400, 300, ['width' => 500, 'height' => 373], ['minWidth' => "500", 'maxHeight' => "100"]],
            [400, 300, ['width' => 670, 'height' => 500], ['minHeight' => "500", 'maxHeight' => "100"]],
            [400, 300, ['width' => 667, 'height' => 500], ['minHeight' => "500", 'maxWidth' => "100"]],
            [400, 300, ['width' => 100, 'height' => 75], ['width' => "100c"]],
            [400, 300, ['width' => 134, 'height' => 100], ['height' => "100c"]],
            [400, 300, ['width' => 100, 'height' => 100], ['width' => "100c", 'height' => "100"]],
            [400, 300, ['width' => 100, 'height' => 100], ['width' => "100c", 'height' => "100c"]],
            [400, 300, ['width' => 100, 'height' => 500], ['width' => "100c", 'minHeight' => "500"]],
            [400, 300, ['width' => 100, 'height' => 375], ['width' => "100c", 'minWidth' => "500"]],
            [400, 300, ['width' => 500, 'height' => 375], ['width' => "500c"]],
            [400, 300, ['width' => 667, 'height' => 500], ['height' => "500c"]],
            [400, 300, ['width' => 500, 'height' => 500], ['width' => "500c", 'height' => "500"]],
            [400, 300, ['width' => 500, 'height' => 500], ['width' => "500c", 'height' => "500c"]],
            [400, 300, ['width' => 133, 'height' => 100], ['width' => "500c", 'maxHeight' => "100"]],
            [400, 300, ['width' => 100, 'height' => 75], ['width' => "100m", 'maxWidth' => "200"]],
            [400, 300, ['width' => 100, 'height' => 75], ['width' => "200m", 'maxWidth' => "100"]],
            [400, 300, ['width' => 100, 'height' => 75], ['width' => "100c+10"]],
            [300, 400, ['width' => 300, 'height' => 400], []],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "100"]],
            [300, 400, ['width' => 75, 'height' => 100], ['height' => "100"]],
            [300, 400, ['width' => 300, 'height' => 400], ['minWidth' => "100"]],
            [300, 400, ['width' => 300, 'height' => 400], ['minHeight' => "100"]],
            [300, 400, ['width' => 100, 'height' => 134], ['maxWidth' => "100"]],
            [300, 400, ['width' => 75, 'height' => 100], ['maxHeight' => "100"]],
            [300, 400, ['width' => 100, 'height' => 100], ['width' => "100", 'height' => "100"]],
            [300, 400, ['width' => 300, 'height' => 400], ['minWidth' => "100", 'minHeight' => "100"]],
            [300, 400, ['width' => 75, 'height' => 100], ['maxWidth' => "100", 'maxHeight' => "100"]],
            [300, 400, ['width' => 500, 'height' => 667], ['width' => "500"]],
            [300, 400, ['width' => 375, 'height' => 500], ['height' => "500"]],
            [300, 400, ['width' => 300, 'height' => 400], ['minWidth' => "500"]],
            [300, 400, ['width' => 300, 'height' => 400], ['minHeight' => "500"]],
            [300, 400, ['width' => 300, 'height' => 400], ['maxWidth' => "500"]],
            [300, 400, ['width' => 300, 'height' => 400], ['maxHeight' => "500"]],
            [300, 400, ['width' => 500, 'height' => 500], ['width' => "500", 'height' => "500"]],
            [300, 400, ['width' => 300, 'height' => 400], ['minWidth' => "500", 'minHeight' => "500"]],
            [300, 400, ['width' => 300, 'height' => 400], ['maxWidth' => "500", 'maxHeight' => "500"]],
            [300, 400, ['width' => 500, 'height' => 667], ['width' => "500", 'minWidth' => "100"]],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "100", 'minHeight' => "100"]],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "100", 'minWidth' => "100", 'minHeight' => "100"]],
            [300, 400, ['width' => 200, 'height' => 267], ['width' => "100", 'minWidth' => "200", 'maxHeight' => "100"]],
            [300, 400, ['width' => 375, 'height' => 500], ['height' => "500", 'minHeight' => "100"]],
            [300, 400, ['width' => 100, 'height' => 100], ['height' => "100", 'minWidth' => "100"]],
            [300, 400, ['width' => 100, 'height' => 100], ['height' => "100", 'minWidth' => "100", 'minHeight' => "100"]],
            [300, 400, ['width' => 100, 'height' => 200], ['width' => "100", 'height' => "100", 'minHeight' => "200"]],
            [300, 400, ['width' => 500, 'height' => 670], ['minWidth' => "500", 'maxWidth' => "100"]],
            [300, 400, ['width' => 500, 'height' => 667], ['minWidth' => "500", 'maxHeight' => "100"]],
            [300, 400, ['width' => 375, 'height' => 500], ['minHeight' => "500", 'maxHeight' => "100"]],
            [300, 400, ['width' => 373, 'height' => 500], ['minHeight' => "500", 'maxWidth' => "100"]],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "100c"]],
            [300, 400, ['width' => 75, 'height' => 100], ['height' => "100c"]],
            [300, 400, ['width' => 100, 'height' => 100], ['width' => "100c", 'height' => "100"]],
            [300, 400, ['width' => 100, 'height' => 100], ['width' => "100c", 'height' => "100c"]],
            [300, 400, ['width' => 100, 'height' => 500], ['width' => "100c", 'minHeight' => "500"]],
            [300, 400, ['width' => 100, 'height' => 670], ['width' => "100c", 'minWidth' => "500"]],
            [300, 400, ['width' => 500, 'height' => 667], ['width' => "500c"]],
            [300, 400, ['width' => 375, 'height' => 500], ['height' => "500c"]],
            [300, 400, ['width' => 500, 'height' => 500], ['width' => "500c", 'height' => "500"]],
            [300, 400, ['width' => 500, 'height' => 500], ['width' => "500c", 'height' => "500c"]],
            [300, 400, ['width' => 75, 'height' => 100], ['width' => "500c", 'maxHeight' => "100"]],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "100m", 'maxWidth' => "200"]],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "200m", 'maxWidth' => "100"]],
            [300, 400, ['width' => 100, 'height' => 134], ['width' => "100c+10"]],
        ];
    }
}
