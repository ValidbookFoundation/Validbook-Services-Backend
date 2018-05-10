<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\card;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "dim_claim_card".
 *
 * @property integer $id
 * @property string $type
 */
class DimClaimCard extends ActiveRecord
{

    const HUMAN_CLAIM_TYPE = "I am human";
    const HUMAN_CLAIM_ID = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dim_claim_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string', 'max' => 255],
        ];
    }
}