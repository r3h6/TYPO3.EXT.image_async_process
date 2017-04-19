<?php

namespace R3H6\ImageAsyncProcess\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Http\Stream;
use R3H6\ImageAsyncProcess\Cache\ProcessConfigurationCache;

/**
 * FileController
 */
class FileController
{
    public function readAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $cacheIdentifier = sha1(ltrim($_SERVER['REQUEST_URI'], '/'));
        $cacheInstance = $this->getCacheInstance();

        try {
            $data = $cacheInstance->get($cacheIdentifier);
        } catch (\RuntimeException $exception) {
            return $response->withStatus(404, 'Invalid cache');
        }

        $file = $this->getFileRepository()->findByIdentifier($data['file']);
        if ($file === null) {
            return $response->withStatus(404, 'File not found');
        }


        $processedFile = $file->process($data['taskType'], $data['configuration']);

        $filePath = PATH_site . $processedFile->getPublicUrl();

        if (!file_exists($filePath)) {
            return $response->withStatus(404, 'File not processed');
        }

        $response = $response->withHeader('Content-Type', (string) $processedFile->getMimeType());
        $response = $response->withHeader('Content-Length', (string) $processedFile->getSize());
        return $response->withBody(new Stream($filePath));
    }

    /**
     * [getCacheInstance description]
     * @return \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
     */
    protected function getCacheInstance()
    {
        return GeneralUtility::makeInstance(ObjectManager::class)->get(ProcessConfigurationCache::class);
    }

    /**
     * [getFileRepository description]
     * @return TYPO3\CMS\Core\Resource\FileRepository
     */
    protected function getFileRepository()
    {
        return GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
    }
}
