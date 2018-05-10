<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;

use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $text
 * @property string $url
 * @property integer $is_new
 * @property integer $is_seen
 * @property integer $created_at
 *
 * @property User $receiver
 * @property User $sender
 */
class Notification extends ActiveRecord
{

    const TYPE_FOLLOW = 1;
    const TYPE_LIKE = 2;
    const TYPE_COMMENT = 3;
    const TYPE_REPLY = 4;
    const TYPE_AUTHOR_ENTITY = 5;
    const TYPE_FOLLOW_BOOK = 6;
    const TYPE_KNOCK_BOOK = 7;
    const TYPE_HUMAN_CARD = 8;
    const TYPE_HUMAN_CARD_FOR_SELF = 9;

    use PaginationTrait;

    public $sender;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'receiver_id'], 'required'],
            [['sender_id', 'receiver_id', 'is_new', 'is_seen', 'created_at'], 'integer'],
            [['text'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sender_id' => Yii::t('app', 'Sender ID'),
            'receiver_id' => Yii::t('app', 'Receiver ID'),
            'text' => Yii::t('app', 'Text'),
            'url' => Yii::t('app', 'Url'),
            'is_new' => Yii::t('app', 'Is New'),
            'is_seen' => Yii::t('app', 'Is Seen'),
            'created_at' => Yii::t('app', 'Created At'),
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


    public function getFormattedData()
    {
        $created = Yii::$app->formatter->asDate($this->created_at);

        $data = [
            'type' => 'notification',
            "id" => $this->id,
            "text" => $this->text,
            "created" => $created,
            'is_new' => $this->is_new,
            'is_seen' => $this->is_seen,
            'link' => $this->url,
            "user" => [
                "id" => $this->sender->getId(),
                "fullName" => $this->sender->getFullName(),
                "slug" => $this->sender->slug,
                "avatar" => $this->sender->getAvatar('48x48', $this->sender->getId())
            ]
        ];

        return $data;
    }

    public function getNotifications($userId)
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $notifications = Notification::find()
            ->where(["receiver_id" => $userId])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $notifications;
    }
}
