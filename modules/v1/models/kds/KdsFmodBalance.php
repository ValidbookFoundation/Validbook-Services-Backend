<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\kds;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "kds_fmod_balance".
 *
 * @property integer $id
 * @property integer $kds_fmod
 * @property integer $timestamp
 */
class KdsFmodBalance extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kds_fmod_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kds_fmod', 'timestamp'], 'integer'],
            [['timestamp'], 'required'],
        ];
    }
}