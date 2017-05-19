<?php

namespace R3H6\ImageAsyncProcess\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;

class FileUtility
{
    const MODE_MAX = 'max';
    const MODE_MIN = 'min';
    const MODE_EXACT = 'exact';

    protected static function parse($size)
    {
        if (preg_match('/(?P<size>[0-9]+)(?P<modifier>c|m)?(?P<offset>\+|\-[0-9]+)?/i', $size, $matches)) {
            return $matches;
        }
    }

    public static function calculateDimensions(FileInterface $file, array $configuration)
    {
        $mode = static::MODE_MAX;
        $fileWidth = (int) $file->getProperty('width');
        $fileHeight = (int) $file->getProperty('height');
        $format = $fileWidth / $fileHeight; // Square: x = 1 , Landscape: x > 1, Portrait: x < 1
        $boxWidth = $fileWidth;
        $boxHeight = $fileHeight;

        if (isset($configuration['width'])) {
            $widthParts = static::parse($configuration['width']);
            $boxWidth = (int) $widthParts['size'];
            if ($widthParts['modifier'] === 'm') {
                $configuration['maxWidth'] = $widthParts['size'];
            } elseif ($widthParts['modifier'] === 'c') {
                $mode = static::MODE_EXACT;
            }
        }

        if (isset($configuration['height'])) {
            $heightParts = static::parse($configuration['height']);
            $boxHeight = (int) $heightParts['size'];
            if ($heightParts['modifier'] === 'm') {
                $configuration['maxHeight'] = $heightParts['size'];
            } elseif ($heightParts['modifier'] === 'c') {
                $mode = static::MODE_EXACT;
            } else {
                $mode = isset($widthParts) ? static::MODE_EXACT: static::MODE_MAX;
            }
        }

        if (isset($configuration['maxWidth'])) {
            $maxWidth = (int) $configuration['maxWidth'];
            $mode = static::MODE_MAX;
            $boxWidth = min($boxWidth, $maxWidth);
        }
        if (isset($configuration['minWidth'])) {
            $minWidth = (int) $configuration['minWidth'];
            if ($minWidth > $boxWidth) {
                $mode = static::MODE_MIN;
            }
            $boxWidth = max($boxWidth, $minWidth);
        }
        if (isset($configuration['maxHeight'])) {
            $maxHeight = (int) $configuration['maxHeight'];
            $mode = static::MODE_MAX;
            $boxHeight = min($boxHeight, $maxHeight);
        }
        if (isset($configuration['minHeight'])) {
            $minHeight = (int) $configuration['minHeight'];
            if ($minHeight > $boxHeight) {
                $mode = static::MODE_MIN;
            }
            $boxHeight = max($boxHeight, $minHeight);
        }

        $boxFormat = $boxWidth / $boxHeight;

        if ($mode === static::MODE_MAX) {
            $factor = ($format > $boxFormat) ? $boxWidth / $fileWidth: $boxHeight / $fileHeight;
            $newWidth = ceil($factor * $fileWidth);
            $newHeight = ceil($factor * $fileHeight);
        } else if ($mode === static::MODE_MIN) {
            $factor = ($format < $boxFormat) ? $boxWidth / $fileWidth: $boxHeight / $fileHeight;
            $newWidth = ceil($factor * $fileWidth);
            $newHeight = ceil($factor * $fileHeight);
        } else {
            $newWidth = $boxWidth;
            $newHeight = $boxHeight;
        }

        return [
            'width' => $newWidth,
            'height' => $newHeight,
        ];
    }
}
