<?php

namespace R3H6\ImageAsyncProcess\Slots;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use R3H6\ImageAsyncProcess\Utility\FileUtility;

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
 * FileProcessingSlot
 */
class FileProcessingSlot
{
    /**
     * Cache
     *
     * @var R3H6\ImageAsyncProcess\Cache\ProcessConfigurationCache
     * @inject
     */
    protected $cacheInstance;

    /**
     * Cache processing information for later processing and mark file as processed.
     *
     * @param  \TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService
     * @param  \TYPO3\CMS\Core\Resource\Driver\DriverInterface        $driver
     * @param  \TYPO3\CMS\Core\Resource\ProcessedFile                 $processedFile
     * @param  FileInterface                                          $file
     * @param  string                                                 $taskType
     * @param  array                                                  $configuration
     * @return void
     */
    public function useNullProcessedFile(\TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService, \TYPO3\CMS\Core\Resource\Driver\DriverInterface $driver, \TYPO3\CMS\Core\Resource\ProcessedFile $processedFile, FileInterface $file, $taskType, array $configuration)
    {
        $size = FileUtility::calculateDimensions($file, $configuration);

        $task = $processedFile->getTask();
        $targetFileName = $task->getTargetFilename();
        $processedFile->setName($targetFileName);
        $processedFile->updateProperties($size);
        $cacheIdentifier = sha1($processedFile->getPublicUrl());

        if ((int) $file->getProperty('width') === (int) $size['width'] && (int) $file->getProperty('height') === (int) $size['height']) {
            $processedFile->setUsesOriginalFile();
        } else {
            $this->cacheInstance->set($cacheIdentifier, [
                'configuration' => $configuration,
                'taskType' => $taskType,
                'file' => $file->getUid(),
                'calculatedSize' => $size,
            ]);
        }
    }
}
