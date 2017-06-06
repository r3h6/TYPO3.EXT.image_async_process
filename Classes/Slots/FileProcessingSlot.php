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

    public function useNullProcessedFile($fileProcessingService, $driver, \TYPO3\CMS\Core\Resource\ProcessedFile $processedFile, FileInterface $file, $taskType, $configuration)
    {
        $size = FileUtility::calculateDimensions($file, $configuration);

        $task = $processedFile->getTask();
        $targetFileName = $task->getTargetFilename();
        $processedFile->setName($targetFileName);
        $processedFile->updateProperties($size);
        $cacheIdentifier = sha1($processedFile->getPublicUrl());

        $this->cacheInstance->set($cacheIdentifier, [
            'configuration' => $configuration,
            'taskType' => $taskType,
            'file' => $file->getUid(),
            'calculatedSize' => $size,
        ]);
    }
}
