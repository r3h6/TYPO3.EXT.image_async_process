<?php

namespace R3H6\ImageAsyncProcess\Cache;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
