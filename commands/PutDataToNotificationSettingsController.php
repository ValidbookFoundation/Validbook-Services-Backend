<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use app\modules\v1\models\notification\NotificationSettings;
use app\modules\v1\models\User;
use yii\console\Controller;

class PutDataToNotificationSettingsController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->all();

        foreach ($users as $user) {

            $model = new NotificationSettings();

            $serializedData = serialize(NotificationSettings::getDefault());

            $model->user_id = $user->id;
            $model->settings = $serializedData;
            $model->save();
        }
    }
}