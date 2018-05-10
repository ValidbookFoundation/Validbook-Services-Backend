<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\traits;

use app\modules\v1\models\Avatar;
use Yii;

trait AvatarTrait
{
    public function getAvatar($size = null, $userId)
    {
        /** @var Avatar $avatar */
        switch ($size) {
            case 'full':
                $url = $this->profile->avatar;
                break;

            case '32x32':
                $avatar = Avatar::find()->where([
                    'user_id' => $userId,
                    'size' => '32x32'
                ])->one();
                $url = (!empty($avatar)) ? $avatar->url : Yii::$app->params['defaultAvatarUrl32'];
                break;

            case '48x48':
                $avatar = Avatar::find()->where([
                    'user_id' => $userId,
                    'size' => '48x48'
                ])->one();
                $url = (!empty($avatar)) ? $avatar->url : Yii::$app->params['defaultAvatarUrl48'];
                break;

            case '100x100':
                $avatar = Avatar::find()->where([
                    'user_id' => $userId,
                    'size' => '100x100'
                ])->one();
                $url = (!empty($avatar)) ? $avatar->url : Yii::$app->params['defaultAvatarUrl100'];
                break;

            case '230x230':
                $avatar = Avatar::find()->where([
                    'user_id' => $userId,
                    'size' => '230x230'
                ])->one();
                $url = (!empty($avatar)) ? $avatar->url : Yii::$app->params['defaultAvatarUrl230'];
                break;

            default:
                $url = $this->profile->avatar;
        }

        return $url;
    }
}