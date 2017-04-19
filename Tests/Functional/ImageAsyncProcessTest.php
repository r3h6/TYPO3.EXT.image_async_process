<?php

namespace R3H6\ImageAsyncProcess\Functional;

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

/**
 * ImageAsyncProcessTest
 */
class ImageAsyncProcessTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    // protected $testExtensionsToLoad = ['typo3conf/ext/image_async_process'];

    protected function setUp()
    {
        parent::setUp();
        $this->importDataSet('Tests/Functional/Fixtures/Database/pages.xml');
        //$this->setUpFrontendRootPage(1, ['EXT:image_async_process/Tests/Functional/Fixtures/TypoScript/setup.ts']);
    }

    /**
     * @test
     */
    public function core()
    {
        $response = $this->getFrontendResponse(1);
        $this->assertSame('expected', $response->getContent());
    }
}
