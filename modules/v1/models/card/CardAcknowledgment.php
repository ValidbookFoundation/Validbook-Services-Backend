<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\card;


use app\modules\v1\models\Signature;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "card_acknowledgment".
 *
 * @property integer $id
 * @property string $card_address
 * @property string $sig_address
 * @property string $hash
 */
class CardAcknowledgment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_acknowledgment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_address', 'sig_address'], 'required'],
            [['hash'], 'string'],
            [['card_address', 'sig_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_address' => 'Card Address',
            'sig_address' => 'Sig Address',
        ];
    }

    public function getSignatureTemplate()
    {
        $result = null;

        $signature = Signature::findOne(['public_address' => $this->sig_address, 'hash' => $this->hash, 'is_revoked' => 0]);

        if ($signature !== null) {
            $result = [
                "address" => $signature->public_address,
//                "message" => [
//                    "acknowledgmentType" => $this->getType(),
//                    "acknowledgedCard" => $this->card_address,
//                    "acknowledgmentFromCard" => $signature->public_address,
//                    "msgDescriptiveText" => $signature->message,
//                    "msgCreateTimestamp" => $signature->getDateTime()
//                ],
                "message" => json_decode($signature->message),
                "signature" => $signature->sig
            ];
        }
        return $result;
    }
}