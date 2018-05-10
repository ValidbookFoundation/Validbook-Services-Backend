<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\notification\Notification;
use app\modules\v1\models\notification\NotificationSettings;
use app\modules\v1\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;


class NotificationController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        $modelUser = Yii::$app->user->identity;
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)){
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $model = new Notification();
        $model->setPage($page);
        $model->setItemsPerPage(10);

        $notifications = $model->getNotifications($modelUser->getId());
        $senders = ArrayHelper::map($notifications, 'id', 'sender_id');
        $userSenders = User::findAll(['id' => array_values($senders)]);
        $userSenders = ArrayHelper::index($userSenders, 'id');

        /** @var \app\modules\v1\models\notification\Notification $item */
        foreach ($notifications as $item){
            $item->sender = $userSenders[$item->sender_id];
            $data[] = $item->getFormattedData();
        }

        return $this->success($data);
    }


    public function actionMarkRead($id)
    {
        /** @var \app\modules\v1\models\notification\Notification $model */
        $model = Notification::find()->where(["id" => $id])->one();

        if (!empty($model)) {

            if ($model->receiver_id == Yii::$app->user->id) {
                $model->is_seen = 1;
                $model->save(false);

                return $this->success();
            } else {
                return $this->failure("Notification does not exists");
            }
        }

        return $this->failure();
    }

    public function actionMarkReadAll()
    {
        $notifications = Notification::find()->where([
            "receiver_id" => Yii::$app->user->id,
            "is_seen" => 0
        ])->all();

        if ($notifications) {
            foreach ($notifications as $model) {
                $model->is_seen = 1;
                $model->save(false);
            }

        }

        return $this->success();
    }

    public function actionMarkSeenAll()
    {
        $notifications = Notification::find()->where([
            "receiver_id" => Yii::$app->user->id,
            "is_new" => 1
        ])->all();

        if ($notifications) {
            foreach ($notifications as $model) {
                $model->is_new = 0;
                $model->save(false);
            }
        }

        return $this->success();
    }

    /**
     * Get notification settings options for current user
     * @return array
     */
    public function actionViewSettings()
    {
        $model = NotificationSettings::find()->where(['user_id' => Yii::$app->user->id]);
        if (!$model->exists()) {
            return $this->failure("Notification settings model does not exists");
        }

        $model = $model->one();

        return $this->success(unserialize($model->settings));
    }

    /**
     * Update notification settings options for current user
     */
    public function actionUpdateSettings()
    {
        $settings = Yii::$app->request->post('settings', '');
        $type = Yii::$app->request->post('notification_type', 'settings'); // settings || updates

        if (!is_array($settings))
            return $this->failure("settings must be an array", 422);

        $model = NotificationSettings::find()->where(['user_id' => Yii::$app->user->id]);
        if (!$model->exists()) {
            return $this->failure("Notification settings model does not exists");
        } else {
            $model = $model->one();
            $newSettings = unserialize($model->settings);
            $newSettings[$type] = $settings;
            $model->updateAttributes(['settings' => serialize($newSettings)]);

            return $this->success('', 201);
        }
    }

    public function actionCountNewNotifications()
    {
        $userId = Yii::$app->user->getId();

        if($userId == null){
        return $this->failure('bad request options', 400);
        }

        $countIsNewNot = (new Query())
            ->select('count(id) as count')
            ->from('notification')
            ->where(['receiver_id' => $userId, 'is_new' => 1])
            ->groupBy('receiver_id')
            ->one();

        $countIsNewConv = (new Query())
            ->select('count(id) as count')
            ->from('conversation_to_message_user')
            ->where(['user_id' => $userId, 'is_new' => 1])
            ->groupBy('user_id')
            ->one();

        $data = ['countNewNotification' => (int)$countIsNewNot['count'], 'countNewConversation' => (int)$countIsNewConv['count']];
        return $this->success($data);
    }
}
