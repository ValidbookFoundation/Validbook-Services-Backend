<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\card;

use app\modules\v1\jobs\AddFullCard;
use app\modules\v1\models\Signature;
use app\modules\v1\traits\GethClientTrait;
use app\modules\v1\traits\PaginationTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "card_claim".
 *
 * @property integer $id
 * @property integer $claim_id
 * @property string $card_address
 * @property string $sig_address
 * @property string $hash
 */
class CardClaim extends ActiveRecord
{
    use GethClientTrait;
    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_claim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['claim_id', 'card_address', 'sig_address'], 'required'],
            [['claim_id'], 'integer'],
            [['card_address', 'sig_address'], 'string', 'max' => 255],
            [['hash'], 'string'],
        ];
    }

    public function getSupportsTemplate()
    {
        $result = null;
        $supports = CardSupport::findAll(['hash' => $this->hash, 'card_address' => $this->card_address]);

        /** @var CardSupport $support */
        foreach ($supports as $support) {
            $result[] = [
                "supportType" => $support->getType(),
                "supportHash" => $support->hash,
                "signature" => $support->getSignatureTemplate()
            ];
        }

    }

    public function getSignatureTemplate()
    {
        $result = null;

        $signature = Signature::findOne(['public_address' => $this->sig_address, 'hash' => $this->hash, 'is_revoked' => 0]);

        if ($signature !== null) {
            $result = [
                "address" => $signature->public_address,
                "message" => json_decode($signature->message),
                "signature" => $signature->sig
            ];
        }
        return $result;
    }

    public function getType()
    {
        return DimClaimCard::findOne($this->claim_id)->type;
    }

    public function validateSig($userId, $signature, $message)
    {

        $hexMessage = $this->hexMessage($message);
        $this->hash = $this->hashMessage($message);

        if ($this->verifySig($hexMessage, $signature, $this->card_address)) {
            $signatureModel = new  Signature();
            $signatureModel->sig = $signature;
            $signatureModel->public_address = $this->card_address;
            $signatureModel->message = $message;
            $signatureModel->hash = $this->hash;
            $signatureModel->created_at = time();

            $awsPath = $userId . '/card/signatures/card_' . $this->card_address . '_' . $this->card_address;
            if (!$signatureModel->saveFileSignature($awsPath)) {
                return false;
            }

            $signatureModel->save();

            $this->sig_address = $signatureModel->public_address;

            if (!$this->save()) {
                return false;
            }

            // job for added full card document
            \Yii::$app->queue->push(new AddFullCard([
                'public_address' => $this->card_address
            ]));

            return true;
        }

        return false;
    }

    public function getSupports()
    {
        $result = null;
        $supports = CardSupport::find()
            ->where(['card_address' => $this->card_address, 'support_id' => DimSupportCard::HUMAN_SUPP_ID])
            ->orderBy('id DESC')
            ->limit(4)
            ->all();

        /** @var CardSupport $support */
        foreach ($supports as $support) {
            if ($support->isMutual()) {
                $result[] = $support->getFormattedCard();
            }

        }
        return $result;
    }

    public function getSupportsWithPagination()
    {
        $result = [];
        $this->setPagination(10, $this->getPage());

        $supports = CardSupport::find()
            ->where(['card_address' => $this->card_address, 'support_id' => DimSupportCard::HUMAN_SUPP_ID])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var CardSupport $support */
        foreach ($supports as $support) {
            if ($support->isMutual()) {
                $result[] = $support->getFormattedCard();
            }

        }
        return $result;
    }

}