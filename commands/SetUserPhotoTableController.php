<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use app\modules\v1\models\Profile;
use app\modules\v1\models\UserPhoto;
use yii\console\Controller;

class SetUserPhotoTableController extends Controller
{
    public function actionIndex()
    {
        $users = Profile::find()->all();

        /** @var Profile $user */
        foreach ($users as $user) {
            if ($user->avatar != null) {
                $userPhoto = new UserPhoto();
                $userPhoto->type = UserPhoto::TYPE_AVATAR;
                $userPhoto->user_id = $user->user_id;
                $userPhoto->url = $user->avatar;
                $userPhoto->save();
            }
            $count = iconv_strlen($user->cover);

            if ($count > 6) {
                $userPhoto = new UserPhoto();
                $userPhoto->type = UserPhoto::TYPE_COVER;
                $userPhoto->user_id = $user->user_id;
                $userPhoto->url = $user->cover;
                $userPhoto->save();
            }
        }

    }

}