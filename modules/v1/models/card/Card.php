<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\card;

use app\modules\v1\models\hcard\HCardDigitalProperty;
use app\modules\v1\models\identity\Identity;
use app\modules\v1\models\Signature;
use app\modules\v1\models\User;
use app\modules\v1\traits\GethClientTrait;
use app\modules\v1\traits\PaginationTrait;
use Aws\S3\Exception\S3Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "card".
 *
 * @property integer $id
 * @property string $public_address
 * @property string $url
 * @property string $hash
 * @property integer $created_at
 * @property integer $is_revoked
 * @property integer $is_valid
 * @property integer $valid_start_date
 * @property integer $valid_end_date
 */
class Card extends ActiveRecord
{

    use GethClientTrait;
    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'is_revoked', 'is_valid', 'valid_start_date', 'valid_end_date'], 'integer'],
            [['public_address', 'url'], 'required'],
            [['public_address', 'url'], 'string', 'max' => 255],
            [['hash'], 'string'],
        ];
    }

    public function getTemplate($fullName)
    {
        $template = "
            # VALIDBOOK ACCOUNT CARD
            
            {$this->public_address} 
            
            ------------------------------------------------------------  
            This public address has been established for:  
            
            ## {$fullName} 
            
            ------------------------------------------------------------  
            ";

        return $template;
    }


    /**
     * @return User| null
     */
    public function getOwnerOfCard()
    {
        $user = null;
        $identity = Identity::find()->where(['public_address' => $this->public_address])->one();

        if ($identity !== null) {
            return $identity->user;
        }

        return null;
    }

    public function getAcknowledgmentsTemplate()
    {
        $result = null;
        $cardAcknow = CardAcknowledgment::findAll(['card_address' => $this->public_address]);

        /** @var CardAcknowledgment $acknow */
        foreach ($cardAcknow as $acknow) {
            $result[] = [
                "acknowledgmentType" => $acknow->getType(),
                "signature" => $acknow->getSignatureTemplate()
            ];
        }

        return $result;
    }

    public function getLPropertiesTemplate()
    {
        $result = null;
        $cardDigitalProperties = HCardDigitalProperty::findAll(['card_address' => $this->public_address]);

        /** @var HCardDigitalProperty $property */
        foreach ($cardDigitalProperties as $property) {
            $result[] = [
                "linkedToCard" => $property->card_address,
                "linkedDigitalPropertyType" => $property->getPropertyTypeName(),
                "propertyName" => $property->getPropertyName(),
                "urlToProperty" => $property->url_property,
                "randomNumberForPostingByOrOnProperty" => $property->random_number,
                "urlToProof" => $property->url_proof,
                "dateOfProof" => $property->getDateProof()
            ];

        }

        return $result;
    }

    public function getClaimTemplate()
    {
        $result = null;
        $cardClaims = CardClaim::findAll(['card_address' => $this->public_address]);

        /** @var CardClaim $cardClaim */
        foreach ($cardClaims as $cardClaim) {
            $result[] = [
                "address" => $cardClaim->card_address,
                "claimHash" => $cardClaim->hash,
                "signature" => $cardClaim->getSignatureTemplate(),
                "supports" => $cardClaim->getSupportsTemplate()
            ];
        }

        return $result;
    }

    public function getFormattedCard()
    {
        return [
            'public_address' => $this->public_address,
            'account_name' => $this->getAccountName(),
            'created' => \Yii::$app->formatter->asDate($this->created_at),
            'is_can_support' => $this->isCanSupport(),
            'full_card_url' => $this->getFullCard(),
            'claims' => $this->getClaims(),
            'linked_digital_properties' => $this->getLinkedProperties()
        ];
    }

    private function getFullCard()
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = \Yii::$app->get('s3');

        $user = $this->getOwnerOfCard();

        $awsPath = $user->id . '/card/' . 'full_card_' . $this->public_address . '.md';

        try {
            $result = $s3->commands()->getUrl($awsPath)->execute();
            return $result;
        } catch (S3Exception $e) {
            return null;
        }
    }

    private function getClaims()
    {
        $result = null;
        $cardClaims = CardClaim::findAll(['card_address' => $this->public_address]);


        /** @var CardClaim $cardClaim */
        foreach ($cardClaims as $cardClaim) {

            $result[] = ['type' => $cardClaim->getType(), 'supports' => $cardClaim->getSupports()];
        }

        return $result;
    }

    private function getLinkedProperties()
    {
        $result = null;
        $properties = HCardDigitalProperty::findAll(['card_address' => $this->public_address]);

        foreach ($properties as $property) {
            $result[] = $property->getFormattedCard();
        }

        return $result;
    }

    public function getShortFormattedCard()
    {
        return [
            'id' => $this->id,
            'public_address' => $this->public_address,
            'account_name' => $this->getAccountName(),
            'created' => \Yii::$app->formatter->asDate($this->created_at),
        ];
    }

    public function getHumanClaimSupports()
    {
        $result = null;
        $cardClaim = CardClaim::findOne(['card_address' => $this->public_address, 'sig_address' => $this->public_address]);
        $cardClaim->setPage($this->getPage());

        if (!empty($cardClaim)) {
            $result = $cardClaim->getSupportsWithPagination();
        }

        return $result;
    }

    public function isCanSupport()
    {
        /** @var User $user */
        $user = \Yii::$app->getUser()->identity;

        if (empty($user)) {
            return false;
        }

        $cardSupporter = Card::findOne(['public_address' => $user->identity->public_address, 'is_revoked' => 0]);
        $cardOwner = Card::findOne(['public_address' => $this->public_address, 'is_revoked' => 0]);

        if ($cardSupporter->public_address === $cardOwner->public_address) {
            return false;
        }

        $cardClaim = CardClaim::findOne(['card_address' => $cardOwner->public_address, 'sig_address' => $cardOwner->public_address]);

        if (empty($cardClaim)) {
            return true;
        }

        $cardSupport = CardSupport::findOne(['card_address' => $cardOwner->public_address, 'sig_address' => $cardSupporter->public_address]);

        if (empty($cardSupport)) {
            return true;
        }

        if (!Signature::isCanValidate($cardSupporter->public_address, $cardSupport->hash)) {
            return false;
        }

        return true;
    }

    public function getAccountName()
    {
        return "test";
    }
}