<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\following;

use app\modules\v1\jobs\FillChannelBookJob;
use app\modules\v1\jobs\UnFillChannelBookJob;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\Channel;
use app\modules\v1\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "follow_book".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $book_id
 * @property integer $channel_id
 * @property integer $is_follow
 * @property integer $is_block
 * @property string $created_at
 *
 * @property Book $book
 * @property User $user
 * @property Channel $channel
 */
class FollowBook extends ActiveRecord
{
    /**
     * @inheritdoc1
     */
    public static function tableName()
    {
        return 'follow_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'channel_id', 'book_id', 'is_follow', 'is_block'], 'required'],
            [['user_id', 'channel_id', 'book_id', 'is_follow', 'is_block'], 'integer'],
            [['created_at'], 'safe'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
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
            'book_id' => 'Book ID',
            'in_mute' => 'In Mute',
            'created_at' => 'Created At',
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
                ]
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'user_id']);
    }


    public function beforeDelete()
    {
        parent::beforeDelete();

        Yii::$app->queue->push(new UnFillChannelBookJob([
            'bookId' => $this->book_id,
            'channelId' => $this->channel_id,
        ]));

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->is_block == 0 and $this->is_follow == 0) {
            $this->delete();
        }

        Yii::$app->queue->push(new FillChannelBookJob([
            'bookId' => $this->book_id,
            'channelId' => $this->channel_id,
            'userId' => $this->user_id
        ]));

    }
}
