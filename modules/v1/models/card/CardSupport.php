<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\card;


use app\modules\v1\jobs\AddFullCard;
use app\modules\v1\models\notification\HumanCardForSelfReceiver;
use app\modules\v1\models\notification\HumanCardReceiver;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\Signature;
use app\modules\v1\models\User;
use app\modules\v1\models\UserKey;
use app\modules\v1\traits\GethClientTrait;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "card_support".
 *
 * @property integer $id
 * @property integer $support_id
 * @property string $card_address
 * @property string $sig_address
 * @property string $hash
 */
class CardSupport extends ActiveRecord
{

    use GethClientTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_support';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_address', 'sig_address', 'support_id'], 'required'],
            [['support_id'], 'integer'],
            [['card_address', 'sig_address'], 'string', 'max' => 255],
            [['hash'], 'string']
        ];
    }

    public function getFormattedCard()
    {
        $signature = Signature::findOne(['public_address' => $this->sig_address, 'hash' => $this->hash, 'is_revoked' => 0]);
        $userKey = UserKey::findOne(['public_address' => $signature->public_address, 'is_revoked' => 0]);
        $user = User::findOne($userKey->user_id);

        return [
            'id' => $this->id,
            'support_address' => $this->sig_address,
            'created' => \Yii::$app->formatter->asDate($signature->created_at),
            'user' => $user->getShortFormattedData()
        ];
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
        return DimSupportCard::findOne($this->support_id)->type;
    }

    public function validateSig($userId, $signature, $message)
    {
        $hexMessage = $this->hexMessage($message);
        $this->hash = $this->hashMessage($message);

        if ($this->verifySig($hexMessage, $signature, $this->sig_address)) {
            $signatureModel = new  Signature();
            $signatureModel->sig = $signature;
            $signatureModel->public_address = $this->sig_address;
            $signatureModel->message = $message;
            $signatureModel->hash = $this->hash;
            $signatureModel->created_at = time();

            $awsPath = $userId . '/card/signatures/card_' . $this->card_address . '_' . $this->sig_address;
            if (!$signatureModel->saveFileSignature($awsPath)) {
                return false;
            }

            $signatureModel->save();

            if (!$this->save()) {
                return false;
            }

            // send notification for validation
            /** @var User $supporter */
            $supporter = \Yii::$app->getUser()->identity;
            $ownerCard = User::findOne($userId);

            if ($this->isMutual()) {
                $card = Card::findOne(['public_address' => $this->card_address, 'is_revoked' => 0]);
                //add push data to redis model
                $signatureModel->addRelationshipToNode($supporter, $ownerCard, $this->sig_address, $this->card_address, $signatureModel->created_at);

                $notBuilder = new NotificationFactory($supporter, $ownerCard->getId(), $card);
                $hCReceiver = new HumanCardForSelfReceiver();
                $receivers = $hCReceiver->getReceiver($ownerCard->getId());
                $receivers = $notBuilder->filterReceivers($receivers);
                $notBuilder->addModel($receivers);
                $notBuilder->build();

                // job for added full card document
                \Yii::$app->queue->push(new AddFullCard([
                    'public_address' => $this->card_address
                ]));

            } else {
                $notBuilder = new NotificationFactory($supporter, $ownerCard->getId(), $signatureModel);
                $hCReceiver = new HumanCardReceiver();
                $receivers = $hCReceiver->getReceiver($ownerCard->getId());
                $receivers = $notBuilder->filterReceivers($receivers);
                $notBuilder->addModel($receivers);
                $notBuilder->build();
            }


            return true;
        }

        return false;
    }

    public function isMutual()
    {
        $support = self::findOne(['card_address' => $this->sig_address, 'sig_address' => $this->card_address]);
        if (!empty($support)) {
            return true;
        }
        return false;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }
}
