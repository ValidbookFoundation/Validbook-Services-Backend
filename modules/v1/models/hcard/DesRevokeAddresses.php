<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\hcard;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "des_revoke_addresses".
 *
 * @property integer $id
 * @property integer $human_card_id
 * @property string $address
 */
class DesRevokeAddresses extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'des_revoke_addresses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['human_card_id', 'address'], 'required'],
            [['human_card_id'], 'integer'],
            [['address'], 'string', 'max' => 255],
        ];
    }
}
