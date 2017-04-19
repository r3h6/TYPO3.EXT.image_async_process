<?php

namespace R3H6\ImageAsyncProcess\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;

class FileUtility
{
    protected static function parse($size)
    {
        if (preg_match('/(?P<size>[0-9]+)(?P<modifier>c|m)?(?P<offset>\+|\-[0-9]+)?/i', $size, $matches)) {
            return $matches;
        }
    }

    public static function calculateDimensions(FileInterface $file, array $configuration)
    {
        $keepProportions = true;

        $width = $file->getProperty('width');
        $height = $file->getProperty('height');

        if (isset($configuration['width'])) {
            $widthParts = static::parse($configuration['width']);
            $width = (int) $widthParts['size'];
            if ($widthParts['modifier'] === 'm') {
                $maxWdith = $width;
            }
        }
        if (isset($configuration['maxWdith'])) {
            $maxWdith = min($width, (int) $configuration['maxWdith']);
        }
        if (isset($configuration['minWdith'])) {
            $minWidth = max($width, (int) $configuration['minWdith']);
        }

        if (isset($configuration['height'])) {
            $heightParts = static::parse($configuration['height']);
            $height = (int) $heightParts['size'];
            if ($widthParts['modifier'] === 'm') {
                $maxHeight = $height;
            }
        }
        if (isset($configuration['maxHeight'])) {
            $maxHeight = min($height, (int) $configuration['maxHeight']);
        }
        if (isset($configuration['minHeight'])) {
            $minHeight = max($height, (int) $configuration['minHeight']);
        }

        if ($keepProportions) {
            $newWidth = $width;
            $newHeight = $height;
        } else {

        }

        return [
            'width' => $newWidth,
            'height' => $newHeight,
        ];
    }
}
