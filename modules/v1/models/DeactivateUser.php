<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "deactivate_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $time_expired
 */
class DeactivateUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deactivate_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'time_expired'], 'required'],
            [['user_id', 'time_expired'], 'integer'],
        ];
    }
}

