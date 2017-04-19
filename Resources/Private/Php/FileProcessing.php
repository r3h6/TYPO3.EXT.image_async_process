<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

$cacheIdentifier = sha1(ltrim($_SERVER['REQUEST_URI'], '/'));
$cacheInstance = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('imageasyncprocess_configuration');
if (($cache = $cacheInstance->get($cacheIdentifier)) === false) {
    return "error";
}

/** @var TYPO3\CMS\Core\Resource\FileRepository $fileRepository */
$fileRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');

$file = $fileRepository->findByIdentifier($cache['file']);

// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($file);
$processedFile = $file->process($cache['taskType'], $cache['configuration']);

// HttpUtility::redirect($processedFile->getPublicUrl(), HttpUtility::HTTP_STATUS_302);

$filePath = PATH_site . $processedFile->getPublicUrl();
if (!file_exists($filePath)) {
    HttpUtility::setResponseCodeAndExit(HttpUtility::HTTP_STATUS_404);
}

header('Content-type: ' . $processedFile->getMimeType());
header("Content-Length: " . $processedFile->getSize());

readfile($filePath);
