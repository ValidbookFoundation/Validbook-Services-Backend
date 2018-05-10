<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;

use app\modules\v1\jobs\UnFillStoryInChannelsAfterLogJob;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\book\BookCustomPermissions;
use app\modules\v1\models\book\BookPermissionSettings;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "story_book".
 *
 * @property integer $id
 * @property integer $story_id
 * @property integer $book_id
 * @property integer $is_pin
 * @property integer $pin_order
 * @property integer $is_moved_to_bin
 * @property integer $created_at
 *
 * @property Book $book
 * @property Story $story
 */
class StoryBook extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['story_id', 'book_id'], 'required'],
            [['story_id', 'book_id', 'created_at', 'is_pin', 'is_moved_to_bin'], 'integer'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
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
            'story_id' => 'Story ID',
            'book_id' => 'Book ID',
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        Yii::$app->queue->push(new FillStoryInChannelsAfterLogJob([
//            'userId' => Yii::$app->getUser()->getId(),
//            'bookId' => $this->book_id,
//            'storyId' => $this->story_id,
//            'date' => $this->created_at
//        ]));
    }

    public function beforeDelete()
    {
        parent::beforeDelete();

        Yii::$app->queue->push(new UnFillStoryInChannelsAfterLogJob([
            'userId' => Yii::$app->getUser()->getId(),
            'bookId' => $this->book_id,
            'storyId' => $this->story_id
        ]));

        return true;
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
    public function getStory()
    {
        return $this->hasOne(Story::className(), ['id' => 'story_id']);
    }

    public function canUserAddStory()
    {
        $userId = Yii::$app->user->id;

        if ($this->book->author_id == $userId) {
            return true;
        }

        $mapPermissions = BookPermissionSettings::mapSettings();
        $key = array_search('can_delete_stories', $mapPermissions);

        $visibility = BookPermissionSettings::findOne(['book_id' => $this->book_id, $mapPermissions[$key]]);
        if ($visibility->permission_state == BookPermissionSettings::PRIVACY_TYPE_CUSTOM) {
            $customModel = BookCustomPermissions::findAll(['custom_id' => $visibility->custom_permission_id]);
            $users = ArrayHelper::getColumn($customModel, 'user_id');
            if (is_array($users) && in_array($userId, $users))
                return true;
        }

        return false;
    }

    public static function getStoryBooks($storyId)
    {
        $booksArr = [];
        $books = Book::find()
            ->innerJoin('story_book sb', 'book.id = sb.book_id')
            ->where(['sb.story_id' => $storyId])
            ->andWhere(["!=", 'book.name', 'root'])
            ->all();

        if (!empty($books)) {
            /** @var Book $book */
            foreach ($books as $book) {
                $booksArr[] = [
                    "id" => $book->id,
                    "name" => $book->name,
                    "slug" => $book->getUrl(),
                    "auto_export" => $book->auto_export,
                    "auto_import" => $book->auto_import
                ];
            }
        }

        return $booksArr;
    }
}
