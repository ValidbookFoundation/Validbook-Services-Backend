<?php

namespace app\modules\v1\models\identity;

use Yii;

/**
 * This is the model class for table "mutual_linking".
 *
 * @property int $id
 * @property int $identity1_id
 * @property int $identity2_id
 */
class MutualLinking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mutual_linking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity1_id', 'identity2_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identity1_id' => 'Identity1 ID',
            'identity2_id' => 'Identity2 ID',
        ];
    }
}
