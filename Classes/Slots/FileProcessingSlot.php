<?php

namespace R3H6\ImageAsyncProcess\Slots;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use R3H6\ImageAsyncProcess\Utility\FileUtility;

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
        ]);
    }
}
