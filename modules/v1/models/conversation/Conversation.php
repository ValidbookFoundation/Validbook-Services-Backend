<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\conversation;

use app\modules\v1\models\Message;
use app\modules\v1\models\MessageToReceiver;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "conversation".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_group
 * @property integer $count_users
 * @property integer $hours_delete_messages
 *
 * @property ConversationToMessageUser[] $conversationToMessageUsers
 */
class Conversation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $receivers;

    use PaginationTrait;

    public static function tableName()
    {
        return 'conversation';
    }

    public static function getConversationId($userIdsString)
    {
        if ($userIdsString !== null) {
            $userIds = explode(",", $userIdsString);
            $userIds[] = \Yii::$app->user->identity->getId();
            if (count($userIds) === 2) {
                $isGroup = 0;
            } else {
                $isGroup = 1;
            }
            $countUsers = count($userIds);
        }

        $subQuery = (new Query())
            ->select('t1.conversation_id, t1.user_id')
            ->distinct()
            ->from('conversation_to_message_user t1')
            ->innerJoin('conversation t2', 't1.conversation_id = t2.id')
            ->where(['t2.is_group' => $isGroup, 't2.count_users' => $countUsers, 't1.user_id' => $userIds]);


        $conversationId = (new Query())
            ->select('u.conversation_id')
            ->from(['u' => $subQuery])
            ->groupBy('u.conversation_id')
            ->having(['COUNT(u.user_id)' => $countUsers])
            ->one();

        return $conversationId;
    }

    public static function getDeleteHours($conversation_id)
    {
        $model = self::findOne($conversation_id);
        return $model->hours_delete_messages;
    }

    public function getAllConversation()
    {
        $itemsPerPage = 15;
        $user = \Yii::$app->getUser()->identity;

        $conversations = ConversationToMessageUser::find()
            ->distinct()
            ->where([
                'user_id' => $user->getId(),
                'is_left' => 0,
                'is_deleted' => 0
            ])->all();

        $this->setPagination($itemsPerPage, $this->getPage());

        $conversationIds = array_unique(ArrayHelper::getColumn($conversations, 'conversation_id'));

        $subQuery = (new Query())
            ->select('mess.conversation_id, MAX(mess.id) as max_id')
            ->from('message_to_receiver t1')
            ->innerJoin('message mess', 't1.message_id = mess.id')
            ->where(['t1.is_deleted' => 0, 't1.is_left' => 0, 't1.user_id' => $user->getId(), 'mess.conversation_id' => $conversationIds])
            ->groupBy('mess.conversation_id');

        $modelsList = Message::find()->alias('mess')
            ->innerJoin(['t2' => $subQuery])
            ->where('mess.id = t2.max_id')
            ->andWhere('mess.id = t2.max_id')
            ->orderBy('mess.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return $modelsList;
    }

    public function getConversation($id)
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $modelList = [];
        $userId = \Yii::$app->getUser()->identity->getId();
        /** @var ConversationToMessageUser $conversation */
        $conversation = ConversationToMessageUser::find()->where([
            'conversation_id' => $id,
            'user_id' => $userId,
            'is_left' => 0,
            'is_deleted' => 0
        ])->one();

        if (!empty($conversation)) {
            $modelList = Message::find()->alias('t1')
                ->distinct()
                ->innerJoin('message_to_receiver t2', 't1.id = t2.message_id')
                ->where(['t1.conversation_id' => $conversation->conversation_id,
                    't2.is_deleted' => 0,
                    't2.is_left' => 0,
                    't2.user_id' => $userId])
                ->orderBy('t1.created_at DESC')
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->all();
        }
        return array_reverse($modelList);
    }

    public static function deleteConversation($id)
    {
        $userId = \Yii::$app->getUser()->getId();

        /** @var ConversationToMessageUser $conversation */
        $conversation = ConversationToMessageUser::find()
            ->where([
                'conversation_id' => $id,
                'user_id' => $userId,
                'is_left' => 0,
                'is_deleted' => 0
            ])
            ->one();

        if (!empty($conversation)) {
            $conversation->is_deleted = 1;
            $conversation->update();

            $allMessagesConversation = MessageToReceiver::find()->alias('t1')
                ->innerJoin('message t2', 't2.id = t1.message_id')
                ->where(['t2.conversation_id' => $conversation->conversation_id, 't1.user_id' => $userId])
                ->distinct()
                ->all();
            /** @var MessageToReceiver $message */
            foreach ($allMessagesConversation as $message) {
                $message->is_deleted = 1;
                $message->update();
            }
            return true;
        }
        return false;
    }

    public static function addMemberConversation($id, $guestId)
    {
        $conversation = Conversation::findOne($id);

        if (!empty($conversation)) {
            if ($conversation->is_group == 0) {
                $conversation = new Conversation();
                $conversation->is_group = 1;
                $conversation->count_users = 3;
                $conversation->save();

                $receiversArray = (new Query())
                    ->select('user_id, is_left')
                    ->from('conversation_to_message_user')
                    ->where(['conversation_id' => $id, 'is_deleted' => 0])
                    ->all();

                $conversation->setReceivers($receiversArray);

            } elseif ($conversation->is_group == 1) {
                $receiversArray = (new Query())
                    ->select('user_id, is_left')
                    ->from('conversation_to_message_user')
                    ->where(['conversation_id' => $conversation->id, 'is_deleted' => 0])
                    ->all();

                $conversation->setReceivers($receiversArray);

                $receiversIds = ArrayHelper::getColumn($receiversArray, 'user_id');

                if (in_array($guestId, $receiversIds)) {
                    $conversationToUser = ConversationToMessageUser::findOne(['user_id' => $guestId, 'conversation_id' => $conversation->id]);
                    $conversationToUser->is_left = 0;
                    $conversationToUser->update();
                }
                $conversation->count_users += 1;
                $conversation->update();
            }
        }
        return $conversation;
    }

    public static function leftConversation($id)
    {
        $userId = \Yii::$app->getUser()->identity->getId();

        /** @var ConversationToMessageUser $conversation */
        $conversation = ConversationToMessageUser::find()
            ->where([
                'conversation_id' => $id,
                'user_id' => $userId,
                'is_left' => 0,
                'is_deleted' => 0
            ])
            ->one();

        if (!empty($conversation)) {
            $conversation->is_left = 1;
            $conversation->update();

            $allMessagesConversation = MessageToReceiver::find()->alias('t1')
                ->innerJoin('message t2', 't2.id = t1.message_id')
                ->where(['t2.conversation_id' => $id, 't1.user_id' => $userId])
                ->distinct()
                ->all();
            if (!empty($allMessagesConversation)) {
                /** @var MessageToReceiver $message */
                foreach ($allMessagesConversation as $message) {
                    $message->is_left = 1;
                    $message->update();
                }
                return true;
            }
        }
        return false;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['is_group', 'count_users', 'hours_delete_messages'], 'integer'],
        ];
    }


    public static function getAllUsersByConversation($conversationId, $userId)
    {
        $result = [];

        $subQuery = (new Query())
            ->select("cm.user_id, max(created_at) md")
            ->from('conversation_to_message_user cm')
            ->leftJoin('message m', 'cm.user_id = m.user_id AND cm.conversation_id = m.conversation_id')
            ->where(['cm.conversation_id' => $conversationId, 'cm.is_deleted' => 0, 'cm.is_left' => 0])
            ->andWhere(['!=', 'cm.user_id', $userId])
            ->groupBy('cm.user_id');

        $users = (new Query())
            ->select('u.*')
            ->from(['author' => $subQuery])
            ->innerJoin('user u', 'author.user_id=u.id')
            ->orderBy('author.md DESC')
            ->all();

        foreach ($users as $user) {
            $profile = new User($user);
            if (!$profile->status) {
                $result[] = [
                    'id' => null,
                    'first_name' => $profile->first_name,
                    'last_name' => $profile->last_name,
                    'slug' => $profile->slug,
                    'status' => $profile->status,
                    'avatar' => $profile->getAvatar('48x48', $profile->getId())

                ];
            } else {
                $result[] = [
                    'id' => $profile->id,
                    'first_name' => $profile->first_name,
                    'last_name' => $profile->last_name,
                    'slug' => $profile->slug,
                    'status' => $profile->status,
                    'avatar' => $profile->getAvatar('48x48', $profile->id)
                ];
            }
        }
        return $result;
    }

    public function setReceivers($receivers)
    {
        $this->receivers = $receivers;
    }

    public function setDeleteHoursMessage(int $hours, User $user)
    {
        $this->hours_delete_messages = $hours;
        $this->update();

        $receivers = ConversationToMessageUser::find()
            ->where(['conversation_id' => $this->id, 'is_left' => 0])
            ->andWhere(['!=', 'user_id', $user->getId()])
            ->all();

        $receiversIds = ArrayHelper::getColumn($receivers, 'user_id');

        $time = $hours == 0 ? 'never' : "{$hours} hours";
        $text = "{$user->getFullName()} changed delete time messages to {$time}";

        $message = new Message([
            'user_id' => $user->getId(),
            'text' => $text,
            'conversation_id' => $this->id,
            'receivers' => $receiversIds,
            'is_new' => 1,
            'is_tech' => 1
        ]);

        if ($message->validate()) {

            $message->save();

            return $message->getFormattedData();
        } else {
            return $message->errors;
        }
    }

}