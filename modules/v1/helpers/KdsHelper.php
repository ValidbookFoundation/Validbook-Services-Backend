<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\helpers;


class KdsHelper
{
    public static function getKDS($number)
    {
        bcscale(2);
        return (double)bcdiv($number, "1000000");
    }
}