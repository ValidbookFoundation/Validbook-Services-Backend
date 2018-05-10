<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\daemons\TcpWorker;
use app\modules\v1\jobs\SendMessageNotificationEmail;
use app\modules\v1\models\conversation\Conversation;
use app\modules\v1\models\conversation\ConversationToMessageUser;
use app\modules\v1\models\notification\MessageNotification;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $text
 * @property integer $is_new
 * @property integer $is_tech
 * @property integer $created_at
 * @property integer $conversation_id
 * @property array $receivers
 *
 * @property User $user
 * @property MessageToReceiver[] $messageToReceivers
 */
class Message extends ActiveRecord
{
    public $receivers = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'text'], 'required'],
            [['user_id', 'is_new', 'created_at', 'conversation_id', 'is_tech'], 'integer'],
            [['text'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'is_new' => 'Is New',
            'created_at' => 'Created At',
            'conversation_id' => 'Conversation ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->conversation_id == null) {
                $newConversation = new Conversation();
                if (count($this->receivers) > 1) {
                    $newConversation->count_users = count($this->receivers) + 1;
                    $newConversation->is_group = 1;
                }
                $newConversation->save();
                $this->conversation_id = $newConversation->id;
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $conversation = ConversationToMessageUser::find()
            ->where(['conversation_id' => $this->conversation_id, 'user_id' => $this->user_id])
            ->one();

        if (empty($conversation)) {
            $conversationForUser = new ConversationToMessageUser(['conversation_id' => $this->conversation_id, 'user_id' => $this->user_id, 'is_new' => 0, 'is_seen' => 1]);
            $conversationForUser->save();
        } else {
            $conversation->is_seen = 1;
            $conversation->is_new = 0;
            $conversation->update();
        }

        $messageReceiverAuthor = new MessageToReceiver(['user_id' => $this->user_id, 'message_id' => $this->id]);
        $messageReceiverAuthor->save();


        foreach ($this->receivers as $receiver) {
            $messageReceiver = new MessageToReceiver(['user_id' => $receiver, 'message_id' => $this->id]);
            $messageReceiver->save();

            $message['message'] = $this->getFormattedData();
            $message['conversation_id'] = $this->conversation_id;

            $conversation = ConversationToMessageUser::find()
                ->where(['conversation_id' => $this->conversation_id, 'user_id' => $receiver])
                ->one();
            if ($conversation !== null) {
                $conversationId = ArrayHelper::getColumn($conversation, 'conversation_id');
            }


            if (empty($conversation)) {
                $conversationForUser = new ConversationToMessageUser([
                    'conversation_id' => $this->conversation_id,
                    'user_id' => $receiver,
                    'is_new' => 1, 'is_seen' => 0]);
                $conversationForUser->save();
            } else {
                /** @var ConversationToMessageUser $conversation */
                $conversation->is_seen = 0;
                $conversation->is_new = 1;
                $conversation->update();
            }

            $countIsSeenConversation = (new Query())
                ->select('user_id, count(id) as count')
                ->from('conversation_to_message_user')
                ->where(['user_id' => $this->receivers, 'is_new' => 1])
                ->groupBy('user_id')
                ->all();
            $countersNewConversation = ArrayHelper::index($countIsSeenConversation, 'user_id');
            $message['countNewConversation'] = (int)$countersNewConversation[$receiver]['count'];
            $message['type'] = 'message';


            $data = ['user' => $receiver, 'message' => json_encode($message)];
            TcpWorker::write($data);

            //sendMessage for email
            $messageNotification = new MessageNotification();
            $receiverUser = User::findOne($receiver);
            $senderUser = User::findOne($this->user_id);
            $message = $messageNotification->getTextForEmail($receiverUser, $senderUser, $conversation);

            Yii::$app->queue->delay(1 * 60)->push(new SendMessageNotificationEmail([
                'email' => $receiverUser->getEmail(),
                'conversationId' => $conversation->conversation_id,
                'receiverId' => $receiver,
                'message' => $message,
                'subject' => $messageNotification->getEmailSubject($senderUser)
            ]));
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageToReceivers()
    {
        return $this->hasMany(MessageToReceiver::className(), ['message_id' => 'id']);
    }

    public function getFormattedData()
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'date' => $this->getDate(),
            'is_tech' => $this->is_tech,
            'user' => $this->getUser(),
            'conversation_id' => $this->conversation_id,
        ];
    }

    protected function getDate()
    {
        return Yii::$app->formatter->asDate($this->created_at, 'dd MMM yyyy HH:mm:ss');
    }

    public function getUser()
    {
        $model = User::findOne($this->user_id);
        return [
            'id' => $model->id,
            'first_name' => $model->first_name,
            'last_name' => $model->last_name,
            'slug' => $model->slug,
            'avatar' => $model->getAvatar('32x32', $model->id)
        ];

    }

    public function isSeenConversation($userId)
    {
        $IsSeenConversation = (new Query())
            ->select('is_seen')
            ->from('conversation_to_message_user')
            ->where(['user_id' => $userId, 'conversation_id' => $this->conversation_id])
            ->one();

        return (int)ArrayHelper::getValue($IsSeenConversation, 'is_seen');

    }

}
