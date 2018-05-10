<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use yii\helpers\ArrayHelper;

class LikeReceiver implements ReceiverInterface
{

    public function getReceiver($users)
    {
        $receivers = [];

        $user = \Yii::$app->getUser();
        if (!empty($users) and !is_array($users)) {
            if ($users != $user->getId()) {
                $model = NotificationSettings::findOne(['user_id' => $users]);
                $settings = unserialize($model->settings);
                $notificationSettings = $settings['settings'];
                $notificationSettingsForWeb = ArrayHelper::map($notificationSettings, 'label', 'web');
                $notificationSettingsForEmail = ArrayHelper::map($notificationSettings, 'label', 'email');
                $receivers[Notification::TYPE_LIKE][] = [
                    'id' => $users,
                    'notSettingsWeb' => $notificationSettingsForWeb,
                    'notSettingsEmail' => $notificationSettingsForEmail
                ];
            }
        }
        return $receivers;
    }
}