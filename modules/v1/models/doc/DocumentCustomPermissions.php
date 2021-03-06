<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;

use app\modules\v1\models\User;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "document_custom_permissions".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $custom_id
 */
class DocumentCustomPermissions extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_custom_permissions';
    }

    public static function getUsers($id)
    {
        $models = self::findAll(['custom_id' => $id]);
        return ArrayHelper::getColumn($models, 'user_id');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'custom_id'], 'required'],
            [['user_id', 'custom_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }


    public static function setValues(array $values, $id = null)
    {
        if ($id != null) {
            $models = self::findAll(['custom_id' => $id]);
            foreach ($models as $model) {
                $model->delete();
            }
        }

        $customId = self::getMaxId();
        foreach ($values as $value) {
            $model = new self();
            $model->custom_id = $customId;
            $model->user_id = $value;
            $model->save();
        }

        return $customId;
    }

    private static function getMaxId()
    {
        $maxId = self::find()->max('custom_id');
        if ($maxId == null) {
            return 1;
        }
        return $maxId + 1;
    }

}
