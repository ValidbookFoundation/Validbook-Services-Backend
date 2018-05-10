<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\daemons\TcpWorker;
use app\modules\v1\jobs\SendNotificationEmail;
use app\modules\v1\models\Profile;
use app\modules\v1\models\redis\CalmReceiver;
use app\modules\v1\models\User;
use Yii;

class NotificationFactory
{
    public $sender;
    public $receiver;
    public $object;
    public $receivers = [];

    public $notificationsWebModels = [];
    public $notificationsEmailModels = [];

    public function __construct(User $sender, $receiver, $object)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->object = $object;
    }

    public function addModel($receivers)
    {
        foreach ($receivers as $type => $items) {
            foreach ($items as $receiver) {
                if ($type == Notification::TYPE_FOLLOW) {
                    $model = new FollowNotification();
                    $model = $this->setModelProperty($model);

                    if ($receiver["notSettingsWeb"]["When someone followed me"]) {
                        $this->receivers[Notification::TYPE_FOLLOW]['notSettingsWeb'][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone followed me"]) {
                        $this->receivers[Notification::TYPE_FOLLOW]['notSettingsEmail'][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_FOLLOW_BOOK) {
                    $model = new FollowBookNotification();
                    $model = $this->setModelProperty($model);

                    if ($receiver["notSettingsWeb"]["When someone followed my book"]) {
                        $this->receivers[Notification::TYPE_FOLLOW_BOOK]['notSettingsWeb'][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone followed my book"]) {
                        $this->receivers[Notification::TYPE_FOLLOW_BOOK]['notSettingsEmail'][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_LIKE) {
                    $model = new LikeNotification();
                    $model = $this->setModelProperty($model);
                    if ($receiver["notSettingsWeb"]["When someone liked my story"]) {
                        $this->receivers[Notification::TYPE_LIKE]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone liked my story"]) {
                        $this->receivers[Notification::TYPE_LIKE]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_REPLY) {
                    $model = new ReplyNotification();
                    $model = $this->setModelProperty($model, $receiver['id']);
                    if ($receiver["notSettingsWeb"]["When someone replied to my comment"]) {
                        $this->receivers[Notification::TYPE_REPLY]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone replied to my comment"]) {
                        $this->receivers[Notification::TYPE_REPLY]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_AUTHOR_ENTITY) {
                    $model = new AuthorStoryNotification();
                    $model = $this->setModelProperty($model, $receiver['id']);
                    if ($receiver["notSettingsWeb"]["When someone commented on my story"]) {
                        $this->receivers[Notification::TYPE_AUTHOR_ENTITY]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone commented on my story"]) {
                        $this->receivers[Notification::TYPE_AUTHOR_ENTITY]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_COMMENT) {
                    $model = new StoryCommentatorsNotification();
                    $model = $this->setModelProperty($model, $receiver['id']);
                    if ($receiver["notSettingsWeb"]["When someone commented on story I commented"]) {
                        $this->receivers[Notification::TYPE_COMMENT]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone commented on story I commented"]) {
                        $this->receivers[Notification::TYPE_COMMENT]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_KNOCK_BOOK) {
                    $model = new KnockBookNotification();
                    $model = $this->setModelProperty($model);
                    if ($receiver["notSettingsWeb"]["When someone knocked on my book"]) {
                        $this->receivers[Notification::TYPE_KNOCK_BOOK]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone knocked on my book"]) {
                        $this->receivers[Notification::TYPE_KNOCK_BOOK]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_HUMAN_CARD) {
                    $model = new HumanCardNotification();
                    $model = $this->setModelProperty($model);
                    if ($receiver["notSettingsWeb"]["When someone validated my Human Card"]) {
                        $this->receivers[Notification::TYPE_HUMAN_CARD]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone validated my Human Card"]) {
                        $this->receivers[Notification::TYPE_HUMAN_CARD]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
                if ($type == Notification::TYPE_HUMAN_CARD_FOR_SELF) {
                    $model = new HumanCardForSelfNotification();
                    $model = $this->setModelProperty($model);
                    if ($receiver["notSettingsWeb"]["When someone validated my Human Card, after I validated their Human Card"]) {
                        $this->receivers[Notification::TYPE_HUMAN_CARD]["notSettingsWeb"][] = $receiver['id'];
                        $this->notificationsWebModels[] = $model;
                    }
                    if ($receiver["notSettingsEmail"]["When someone validated my Human Card, after I validated their Human Card"]) {
                        $this->receivers[Notification::TYPE_HUMAN_CARD]["notSettingsEmail"][] = $receiver['id'];
                        $this->notificationsEmailModels[] = $model;
                    }
                }
            }
        }

    }

    protected function sendWebNotification($notification)
    {
        foreach ($this->receivers as $key => $receivers) {
            foreach ($receivers['notSettingsWeb'] as $receiver) {
                if ($key == Notification::TYPE_FOLLOW and $notification->getClassName() == "FollowNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_FOLLOW_BOOK and $notification->getClassName() == "FollowBookNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_LIKE and $notification->getClassName() == "LikeNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_REPLY and $notification->getClassName() == "ReplyNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_AUTHOR_ENTITY and $notification->getClassName() == "AuthorStoryNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_COMMENT and $notification->getClassName() == "StoryCommentatorsNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_KNOCK_BOOK and $notification->getClassName() == "KnockBookNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_HUMAN_CARD and $notification->getClassName() == "HumanCardNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
                if ($key == Notification::TYPE_HUMAN_CARD_FOR_SELF and $notification->getClassName() == "HumanCardForSelfNotification") {
                    $data = ['user' => $receiver, 'message' => json_encode($notification->getFormattedData())];
                    TcpWorker::write($data);
                }
            }
        }
    }

    private function sendEmailNotification($notification)
    {
        foreach ($this->receivers as $key => $receivers) {
            foreach ($receivers['notSettingsEmail'] as $receiver) {
                $receiverUser = User::findOne($receiver);

                if ($notification->getClassName() == "FollowNotification") {
                    $emailSubject = $notification->getEmailSubject($receiverUser);
                } else {
                    $emailSubject = $notification->getEmailSubject($notification->sender);
                }

                Yii::$app->queue->push(new SendNotificationEmail([
                    'email' => $receiverUser->getEmail(),
                    'notificationClassName' => $notification->getClassName(),
                    'message' => $notification->getTextForEmail($receiverUser, $notification->sender, $this->object),
                    'subject' => $emailSubject
                ]));
            }
        }
    }

    public function build()
    {
        foreach ($this->notificationsWebModels as $model) {
            $this->sendWebNotification($model);
        }

        foreach ($this->notificationsEmailModels as $model) {
            $this->sendEmailNotification($model);
        }
    }

    private function setModelProperty($model, $receiverId = null)
    {
        $model->sender_id = $this->sender->getId();
        if ($receiverId != null) {
            $model->receiver_id = $receiverId;
        } else {
            $model->receiver_id = $this->receiver;
        }
        $model->is_seen = 0;
        $model->is_new = 1;
        $model->text = $model->getText($this->sender, $this->object);
        $model->url = $model->getUrl($this->object);
        $model->sender = $this->sender;

        $model->save();

        return $model;
    }

    public function filterReceivers($receivers)
    {
        $result = [];

        foreach ($receivers as $key => $dataValues) {
            foreach ($dataValues as $k => $receiver){
                $profile = Profile::findOne(['user_id' => $receiver['id']]);
                if($profile->calm_mode_notifications == false){
                    $result[$key][$k] = $receiver;
                }else{
                    $calmReceiver = new CalmReceiver();
                    $calmReceiver->sender = json_encode($this->sender->toArray());
                    $calmReceiver->receiver_id = $this->receiver;
                    $calmReceiver->receivers = json_encode($receivers);
                    $calmReceiver->object = json_encode($this->object->toArray());
                    $calmReceiver->name_object = $this->object->getClassName();
                    $calmReceiver->save();
                }
            }
        }

        return $result;
    }

}