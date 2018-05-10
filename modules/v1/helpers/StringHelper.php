<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\helpers;

class StringHelper
{
    public static function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    public static function base64ToJpeg($base64_string, $output_file)
    {
        // open the output file for writing
        $ifp = fopen($output_file, 'w+');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }

    public static function parseSignature($str)
    {
        $str = preg_replace("/<\?---/", "", $str);
        $str = preg_replace("/---\?>/", "", $str);
        $str = preg_replace("/}/", "},", $str);
        $str = preg_replace("/{/", "{", $str);
        $str = preg_replace("/\(\(\(\(\(START SIGNATURES\)\)\)\)\)/", "[", $str);
        $str = preg_replace("/\(\(\(\(\(END SIGNATURES\)\)\)\)\)/", "]", $str);
        $str = preg_replace("/START SIGNATURE/", "", $str);
        $str = preg_replace("/END SIGNATURE/", "", $str);
        $strTep = strrpos($str, "}");
        $str = substr($str, 0, $strTep + 1);
        $str .= "]";
        $arr = json_decode($str, true);

        return $arr;
    }
}
