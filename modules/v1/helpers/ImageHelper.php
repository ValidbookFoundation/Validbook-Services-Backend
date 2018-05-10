<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\helpers;


class ImageHelper
{
    const MAX_SIZE = 2048;
    const SMALL_SIZE = 220;

    const KEY_THUMBNAIL = 'thumbnail';
    const KEY_ORIGINAL = 'original';

    public static function addSmallSizes(string $key, string $size): array
    {
        $result['width'] = null;
        $result['height'] = null;

        $pieces = explode("x", $size);

        switch ($key) {
            case 'thumbnail':
                $result['width'] = (int)$pieces[0];
                $result['height'] = (int)$pieces[1];
                break;
            case 'original':
                $width = (int)$pieces[0];
                $height = (int)$pieces[1];

                if ($height === $width) {
                    $result['width'] = self::SMALL_SIZE;
                    $result['height'] = self::SMALL_SIZE;
                }

                if ($width > $height) {
                    $scale = round($height / self::SMALL_SIZE, 2, PHP_ROUND_HALF_UP);
                    $result['height'] = self::SMALL_SIZE;
                    $result['width'] = (int)($width / $scale);
                } else {
                    $scale = round($width / self::SMALL_SIZE, 2, PHP_ROUND_HALF_UP);
                    $result['width'] = self::SMALL_SIZE;
                    $result['height'] = (int)($height / $scale);
                }
                break;
        }

        return $result;
    }

    public static function checkOriginalSize(string $key, string $size): array
    {
        $result['width'] = null;
        $result['height'] = null;

        $pieces = explode("x", $size);

        $width = (int)$pieces[0];
        $height = (int)$pieces[1];

        switch ($key) {
            case self::KEY_ORIGINAL:
                if ($width < self::MAX_SIZE && $height < self::MAX_SIZE) {
                    $result['width'] = $width;
                    $result['height'] = $height;
                }

                if ($width > self::MAX_SIZE && $height < self::MAX_SIZE) {
                    $scale = round($width / self::MAX_SIZE, 2, PHP_ROUND_HALF_UP);
                    $result['width']  = self::MAX_SIZE;
                    $result['height'] = (int)($height / $scale);
                }elseif($width < self::MAX_SIZE && $height > self::MAX_SIZE){
                    $scale = round($height / self::MAX_SIZE, 2, PHP_ROUND_HALF_UP);
                    $result['height'] = self::MAX_SIZE;
                    $result['width']  = (int)($width / $scale);
                }

                if ($width > self::MAX_SIZE && $height > self::MAX_SIZE) {
                    if ($width > $height) {
                        $scale = round($width / self::MAX_SIZE, 2, PHP_ROUND_HALF_UP);
                        $result['width'] = self::MAX_SIZE;
                        $result['height'] = (int)($height / $scale);
                    } elseif ($height > $width) {
                        $scale = round($height / self::MAX_SIZE, 2, PHP_ROUND_HALF_UP);
                        $result['height'] = self::MAX_SIZE;
                        $result['width'] = (int)($width / $scale);
                    } elseif ($height === $width) {
                        $result['height'] = self::MAX_SIZE;
                        $result['width'] = self::MAX_SIZE;
                    }
                }

                break;
        }

        return $result;

    }
}