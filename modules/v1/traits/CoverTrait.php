<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\traits;

use Yii;

trait CoverTrait
{

    public function getCover($cover, $entityName)
    {
        switch ($entityName) {
            case "User":
                $color = Yii::$app->params['defaultUserCoverColor'];
                break;
            case "Book":
                $color = Yii::$app->params['defaultBookCoverColor'];
                break;
            default:
                $color = Yii::$app->params['defaultUserCoverColor'];
        }

        if ($cover == null) {
            $result = [
                'picture_original' => null,
                'picture_small' => null,
                'color' => $color
            ];
        } else {
            $count = iconv_strlen($cover);

            if ($count > 0 and $count <= 6) {
                $result = [
                    'picture' => null,
                    'color' => $cover
                ];
            } elseif ($count > 6) {
                $result = [
                    'picture' => $cover,
                    'color' => null
                ];
            } else {
                $result = [
                    'picture' => null,
                    'color' => $color
                ];
            }
        }

        return $result;
    }
}