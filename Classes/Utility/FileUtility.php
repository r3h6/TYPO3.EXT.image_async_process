<?php

namespace R3H6\ImageAsyncProcess\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

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
 * FileUtility
 */
class FileUtility
{
    protected static function parse($size)
    {
        if (preg_match('/(?P<size>[0-9]+)(?P<modifier>c|m)?(?P<offset>\+|\-[0-9]+)?/i', $size, $matches)) {
            return $matches;
        }
    }

    protected static function resizeByWidth(&$dimensions, $width, $round = false)
    {
        $factor =  $width / $dimensions['width'];
        if ($round) {
            $dimensions['width'] = round($factor * $dimensions['width']);
            $dimensions['height'] = round($factor * $dimensions['height']);
        } else {
            $dimensions['width'] = ceil($factor * $dimensions['width']);
            $dimensions['height'] = ceil($factor * $dimensions['height']);
        }
    }

    protected static function resizeByHeight(&$dimensions, $height, $round = false)
    {
        $factor =  $height / $dimensions['height'];
        if ($round) {
            $dimensions['width'] = round($factor * $dimensions['width']);
            $dimensions['height'] = round($factor * $dimensions['height']);
        } else {
            $dimensions['width'] = ceil($factor * $dimensions['width']);
            $dimensions['height'] = ceil($factor * $dimensions['height']);
        }
    }

    public static function calculateDimensions(FileInterface $file, array $configuration)
    {
        $scaleUp = false;
        $width = null;
        $height = null;
        $maxWidth = null;
        $maxHeight = null;
        $minWidth = null;
        $minHeight = null;
        $cropWidth = false;
        $cropHeight = false;

        $dimensions = [
            'width' => $file->getProperty('width'),
            'height' => $file->getProperty('height'),
        ];

        if (isset($configuration['width'])) {
            $widthParts = static::parse($configuration['width']);
            $width = (int) $widthParts['size'];
            if ($widthParts['modifier'] === 'm') {
                if ($width < $dimensions['width']) {
                    static::resizeByWidth($dimensions, $width);
                }
            } elseif ($widthParts['modifier'] === 'c') {
                $cropWidth = true;
                static::resizeByWidth($dimensions, $width);
            } else {
                static::resizeByWidth($dimensions, $width);
            }
        }

        if (isset($configuration['height'])) {
            $heightParts = static::parse($configuration['height']);
            $height = (int) $heightParts['size'];
            if ($heightParts['modifier'] === 'm') {
                if ($height < $dimensions['height']) {
                    static::resizeByHeight($dimensions, $height);
                }
            } elseif ($heightParts['modifier'] === 'c') {
                $cropHeight = true;
                if ($cropWidth) {
                    $dimensions['height'] = $height;
                } else {
                    static::resizeByHeight($dimensions, $height);
                }
            } else {
                if ($width !== null) {
                    $dimensions['height'] = $height;
                } else {
                    static::resizeByHeight($dimensions, $height);
                }
            }
        }

        if (isset($configuration['maxWidth'])) {
            $maxWidth = (int) $configuration['maxWidth'];
            if ($maxWidth < $dimensions['width']) {
                static::resizeByWidth($dimensions, $maxWidth, ($cropWidth || $cropHeight));
            }
        }
        if (isset($configuration['maxHeight'])) {
            $maxHeight = (int) $configuration['maxHeight'];
            if ($maxHeight < $dimensions['height']) {
                static::resizeByHeight($dimensions, $maxHeight, ($cropWidth || $cropHeight));
            }
        }

        $scaleUp = ($width || $height || $maxWidth || $maxHeight);

        if (isset($configuration['minWidth'])) {
            $minWidth = (int) $configuration['minWidth'];
            if ($minWidth > $dimensions['width']) {
                if ($height) {
                    $dimensions['width'] = $minWidth;
                } else if ($scaleUp) {
                    static::resizeByWidth($dimensions, $minWidth, true);
                }
            }
        }

        if (isset($configuration['minHeight'])) {
            $minHeight = (int) $configuration['minHeight'];
            if ($minHeight > $dimensions['height']) {
                if ($width) {
                    $dimensions['height'] = $minHeight;
                } else if ($scaleUp) {
                    static::resizeByHeight($dimensions, $minHeight, true);
                }
            }
        }

        if ($cropWidth && $width < $dimensions['width']) {
            $dimensions['width'] = $width;
        }

        if ($cropHeight && $height < $dimensions['height']) {
            $dimensions['height'] = $height;
        }

        return $dimensions;
    }
}
