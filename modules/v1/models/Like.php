<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\models\following\Follow;
use app\modules\v1\models\story\Story;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "like".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $story_id
 * @property integer $photo_id
 * @property integer $object_id
 * @property string $model
 * @property integer $created_at
 *
 * @property User $sender
 * @property Story $story
 */
class Like extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'like';
    }

    public static function isLikedStory($id, $userId)
    {
        $model = self::find()->where(['story_id' => $id, 'sender_id' => $userId, 'model' => 'story'])->one();
        if (!empty($model)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'object_id', 'model'], 'required'],
            [['sender_id', 'story_id', 'photo_id', 'object_id', 'created_at'], 'integer'],
            [['model'], 'string', 'max' => 255],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['story_id'], 'exist', 'skipOnError' => true, 'targetClass' => Story::className(), 'targetAttribute' => ['story_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'story_id' => 'Story ID',
            'photo_id' => 'Photo ID',
            'object_id' => 'Object ID',
            'model' => 'Model',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStory()
    {
        return $this->hasOne(Story::className(), ['id' => 'story_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photo::className(), ['id' => 'story_id']);
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
            ]
        ];
    }

    /*
     *
     */
    public static function getStoryLikes($story)
    {
        $likerList = [];
        $isLiked = false;
        $userId = Yii::$app->user->getId();

        if (!empty($story->likes)) {
            foreach ($story->likes as $like) {
                if($like->sender->status == User::STATUS_ACTIVE){
                    $user = [
                        "user" => [
                            "id" => $like->sender->id,
                            "fullName" => $like->sender->fullName,
                            "slug" => $like->sender->slug,
                            "avatar" => $like->sender->getAvatar('32x32', $like->sender->id),
                            "is_friend" => false,
                        ]
                    ];

                    if (Follow::isFriend($userId, $like->sender->id)) {
                        $user["user"]["is_friend"] = true;

                        array_unshift($likerList, $user);

                    } else
                        $likerList[] = $user;

                    //check if current user liked this story
                    if ($like->sender->id == $userId)
                        $isLiked = true;
                }
            }
        }

        $data = [
            'qty' => !empty($story->likes) ? count($story->likes) : 0,
            'is_liked' => $isLiked,
            'people_list' => $likerList
        ];

        return $data;
    }

    /***
     * @param $storyId
     * @return array
     * TODO: GET PHOTOS LIKES
     */
    public static function getPhotoLikes($storyId)
    {
        return [];
    }

}
