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
        $url = $_SERVER['REQUEST_URI'];
        $cacheIdentifier = sha1(ltrim($url, '/'));
        $cacheInstance = $this->getCacheInstance();

        try {
            $data = $cacheInstance->get($cacheIdentifier);
        } catch (\RuntimeException $exception) {
            $this->getLogger()->error('Invalid cache for ' . $url);
            return $response->withStatus(404, 'Invalid cache');
        }

        $file = $this->getFileRepository()->findByIdentifier($data['file']);
        if ($file === null) {
            $this->getLogger()->error('File not found for ' . $url);
            return $response->withStatus(404, 'File not found');
        }


        $processedFile = $file->process($data['taskType'], $data['configuration']);

        $filePath = PATH_site . $processedFile->getPublicUrl();

        if (!file_exists($filePath)) {
            $this->getLogger()->error('Processed file does not exists ' . $filePath);
            return $response->withStatus(404, 'Processed file does not exist');
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

    /**
     * Get class logger
     *
     * @return TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger ()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
    }
}
