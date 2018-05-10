<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\book;

use app\modules\v1\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "knock_book".
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $book_author_id
 * @property integer $user_id
 * @property integer $created_at
 */
class KnockBook extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'knock_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'book_author_id', 'user_id'], 'required'],
            [['book_id', 'book_author_id', 'user_id', 'created_at'], 'integer'],
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

    public function getListBooks($userId)
    {
        $result = [];
        $listModels = self::findAll(['book_author_id' => $userId]);

        foreach ($listModels as $model) {
            $result[] = $model->format();
        }
        return $result;

    }

    public function getKnockBook($userId, $bookId)
    {
        $result = [];
        $listModels = self::findAll(['book_author_id' => $userId, 'book_id' => $bookId]);

        foreach ($listModels as $model) {
            $result[] = $model->format();
        }
        return $result;
    }

    protected function format()
    {
        $knocker = User::findOne($this->user_id);
        $book = Book::findOne($this->book_id);

        $bookArray = [
            'id' => $book->id,
            'name' => $book->name,
            'slug' => $book->slug,
            'icon' => $book->getIcon()
        ];
        $knockerArray = [
            'id' => $knocker->getId(),
            'first_name' => $knocker->first_name,
            'last_name' => $knocker->last_name,
            'avatar' => $knocker->getAvatar('48x48', $knocker->getId())
        ];
        $data = [
            'id' => $this->id,
            'book' => $bookArray,
            'knocker' => $knockerArray
        ];

        return $data;
    }

    public function submitKnock()
    {
        $bookSetting = BookPermissionSettings::findOne(['book_id' => $this->book_id, 'permission_id' => 2]);
        if ($bookSetting->permission_state == 0) {
            $bookSetting->permission_state = 2;

            $bookCustomId = BookCustomPermissions::getMaxId();
            $bookCustom = new BookCustomPermissions();
            $bookCustom->custom_id = $bookCustomId;
            $bookCustom->user_id = $this->user_id;
            $bookCustom->save();

            $bookSetting->custom_permission_id = $bookCustomId;
            $bookSetting->update();
            $this->delete();

            return true;

        } elseif ($bookSetting->permission_state == 2) {
            $bookCustomUsers = BookCustomPermissions::findAll(['custom_id' => $bookSetting->custom_permission_id]);
            $bookCustomUserIds = ArrayHelper::getColumn($bookCustomUsers, 'user_id');
            if (in_array($this->user_id, $bookCustomUserIds)) {
                return false;
            }
            $model = new BookCustomPermissions();

            $model->custom_id = $bookSetting->custom_permission_id;
            $model->user_id = $this->user_id;
            $model->save();
            $this->delete();

            return true;
        }

        return false;
    }

}
