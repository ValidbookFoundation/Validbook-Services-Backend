<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\modules\v1\models\Comment;
use app\modules\v1\models\story\Story;
use yii\helpers\ArrayHelper;

class CommentReceiver implements ReceiverInterface
{
    public $entity_id;
    public $parent_id;
    public $identityUser;

    public function getReceiver($users)
    {
        $receivers = [];


        if (empty($users)) {
            $entity = Story::findOne($this->entity_id);

            $entityAuthor = $entity->user_id;

            $allCommentatorsEntity = ArrayHelper::getColumn(Comment::find()
                ->select('created_by')
                ->distinct()
                ->where(['entity_id' => $this->entity_id, 'entity' => 'story'])
                ->all(),
                'created_by');

            $parentCommentAuthorId = [];

            if ($this->parent_id != 0) {

                $parentComment = Comment::find()
                    ->where(['id' => $this->parent_id])
                    ->one();

                $parentCommentAuthorId = $parentComment->created_by;
            }


            if (($key = array_search($parentCommentAuthorId, $allCommentatorsEntity)) !== false) {
                unset($allCommentatorsEntity[$key]);
            }
            if (($key = array_search($entityAuthor, $allCommentatorsEntity)) !== false) {
                unset($allCommentatorsEntity[$key]);
            }
            if (($key = array_search($this->identityUser->getId(), $allCommentatorsEntity)) !== false) {
                unset($allCommentatorsEntity[$key]);
            }

            if ($entityAuthor == $parentCommentAuthorId) {
                $parentCommentAuthorId = [];
            }

            $receivers[Notification::TYPE_AUTHOR_ENTITY] = $entityAuthor;
            $receivers[Notification::TYPE_COMMENT] = $allCommentatorsEntity;
            $receivers[Notification::TYPE_REPLY] = $parentCommentAuthorId;

            if ($entityAuthor == $this->identityUser->getId()) {
                $receivers[Notification::TYPE_AUTHOR_ENTITY] = [];
            }
            if ($parentCommentAuthorId == $this->identityUser->getId()) {
                $receivers[Notification::TYPE_REPLY] = [];
            }

            if (!empty($receivers[Notification::TYPE_AUTHOR_ENTITY])) {
                $model = NotificationSettings::findOne(['user_id' => $entityAuthor]);
                $settings = unserialize($model->settings);
                $notificationSettings = $settings['settings'];
                $notificationSettingsForWeb = ArrayHelper::map($notificationSettings, 'label', 'web');
                $notificationSettingsForEmail = ArrayHelper::map($notificationSettings, 'label', 'email');
                $receivers[Notification::TYPE_AUTHOR_ENTITY] = [];
                $receivers[Notification::TYPE_AUTHOR_ENTITY][] = [
                    'id' => $entityAuthor,
                    'notSettingsWeb' => $notificationSettingsForWeb,
                    'notSettingsEmail' => $notificationSettingsForEmail
                ];
            }

            if (!empty($receivers[Notification::TYPE_COMMENT])) {
                foreach ($receivers[Notification::TYPE_COMMENT] as $key => $receiver) {
                    $model = NotificationSettings::findOne(['user_id' => $receiver]);
                    $settings = unserialize($model->settings);
                    $notificationSettings = $settings['settings'];
                    $notificationSettingsForWeb = ArrayHelper::map($notificationSettings, 'label', 'web');
                    $notificationSettingsForEmail = ArrayHelper::map($notificationSettings, 'label', 'email');
                    $receivers[Notification::TYPE_COMMENT][] = [
                        'id' => $receiver,
                        'notSettingsWeb' => $notificationSettingsForWeb,
                        'notSettingsEmail' => $notificationSettingsForEmail
                    ];
                    unset($receivers[Notification::TYPE_COMMENT][$key]);
                }
            }

            if (!empty($receivers[Notification::TYPE_REPLY])) {
                $model = NotificationSettings::findOne(['user_id' => $parentCommentAuthorId]);
                $settings = unserialize($model->settings);
                $notificationSettings = $settings['settings'];
                $notificationSettingsForWeb = ArrayHelper::map($notificationSettings, 'label', 'web');
                $notificationSettingsForEmail = ArrayHelper::map($notificationSettings, 'label', 'email');
                $receivers[Notification::TYPE_REPLY] = [];
                $receivers[Notification::TYPE_REPLY][] = [
                    'id' => $parentCommentAuthorId,
                    'notSettingsWeb' => $notificationSettingsForWeb,
                    'notSettingsEmail' => $notificationSettingsForEmail
                ];
            }
        }

        return $receivers;
    }
}