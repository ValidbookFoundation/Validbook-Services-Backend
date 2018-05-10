<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use yii\helpers\ArrayHelper;

class FollowBookReceiver implements ReceiverInterface
{

    public function getReceiver($users)
    {
        $receivers = [];

        if (!empty($users) and !is_array($users)) {
            $model = NotificationSettings::findOne(['user_id' => $users]);
            $settings = unserialize($model->settings);
            $notificationSettings = $settings['settings'];
            $notificationSettingsForWeb = ArrayHelper::map($notificationSettings, 'label', 'web');
            $notificationSettingsForEmail = ArrayHelper::map($notificationSettings, 'label', 'email');
            $receivers[Notification::TYPE_FOLLOW_BOOK][] = [
                'id' => $users,
                'notSettingsWeb' => $notificationSettingsForWeb,
                'notSettingsEmail' => $notificationSettingsForEmail
            ];
        }

        return $receivers;
    }
}