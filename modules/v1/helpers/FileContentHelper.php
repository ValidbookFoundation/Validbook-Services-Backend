<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\helpers;


use GuzzleHttp\Psr7\Stream;

class FileContentHelper
{

    public static function getContent($path)
    {
        $resource = fopen($path, 'r');
        $stream = new Stream($resource);

        return $stream->getContents();
    }

    public static function get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($code == 200) {
            curl_close($ch);
            return $data;
        }

        return false;
    }
}