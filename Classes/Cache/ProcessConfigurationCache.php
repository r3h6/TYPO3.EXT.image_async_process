<?php

namespace R3H6\ImageAsyncProcess\Cache;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * ProcessConfigurationCache
 */
class ProcessConfigurationCache
{
    const LIFETIME_FOREVER = 0;
    protected static $id = 'imageasyncprocess_configuration';

    /**
     * CacheInstance
     *
     * @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
     */
    protected $cacheInstance;


    public function __construct()
    {
        $this->cacheInstance = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->getCache(static::$id);
    }

    public function get($entryIdentifier)
    {
        $data = $this->cacheInstance->get($entryIdentifier);
        if ($data === false) {
            throw new \RuntimeException("No cache for identifier '$entryIdentifier'", 1492023509);
        }
        return (array) $data;
    }

    public function set($entryIdentifier, array $data)
    {
        $this->cacheInstance->set($entryIdentifier, $data, [], self::LIFETIME_FOREVER);
    }
}
