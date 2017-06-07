<?php

namespace R3H6\ImageAsyncProcess\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Http\Stream;
use R3H6\ImageAsyncProcess\Cache\ProcessConfigurationCache;

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
 * FileController
 */
class FileController
{
    const EXT_KEY = 'image_async_process';

    /**
     * Process a file and send it.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function processAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = $_SERVER['REQUEST_URI'];
        $cacheIdentifier = sha1(ltrim($url, '/'));
        $cacheInstance = $this->getCacheInstance();

        // $this->getLogger()->debug('Process ' . $url);

        try {
            $data = $cacheInstance->get($cacheIdentifier);
        } catch (\RuntimeException $exception) {
            $this->getLogger()->error('Invalid cache for ' . $url, ['cacheIdentifier' => $cacheIdentifier]);
            GeneralUtility::sysLog('Invalid cache for ' . $url, self::EXT_KEY, GeneralUtility::SYSLOG_SEVERITY_ERROR);
            return $response->withStatus(404, 'Invalid cache');
        }

        $file = $this->getFileRepository()->findByIdentifier($data['file']);
        if ($file === null) {
            $this->getLogger()->error('File not found for ' . $url, ['data' => $data]);
            GeneralUtility::sysLog('File not found for ' . $url, self::EXT_KEY, GeneralUtility::SYSLOG_SEVERITY_ERROR);
            return $response->withStatus(404, 'File not found');
        }


        $processedFile = $file->process($data['taskType'], $data['configuration']);

        $filePath = PATH_site . $processedFile->getPublicUrl();

        if (!file_exists($filePath)) {
            $this->getLogger()->error('Processed file does not exists ' . $filePath, ['data' => $data]);
            GeneralUtility::sysLog('Processed file does not exists ' . $filePath, self::EXT_KEY, GeneralUtility::SYSLOG_SEVERITY_ERROR);
            return $response->withStatus(404, 'Processed file does not exist');
        }

        if ((int) $processedFile->getProperty('width') !== (int) $data['calculatedSize']['width']
            || (int) $processedFile->getProperty('height') !== (int) $data['calculatedSize']['height']
        ) {
            $this->getLogger()->debug('Precalculated wrong size', [
                'processedFile' => [
                    'width' => $processedFile->getProperty('width'),
                    'height' => $processedFile->getProperty('height'),
                ],
                'data' => $data,
            ]);
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
    protected function getLogger()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
    }
}
