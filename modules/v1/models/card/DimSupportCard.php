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
class DimSupportCard extends ActiveRecord
{

    const HUMAN_SUPP_TYPE = "You are human";
    const HUMAN_SUPP_ID = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dim_support_card';
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