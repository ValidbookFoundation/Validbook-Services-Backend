<?php

namespace app\modules\v1\models\identity;

use app\modules\v1\models\User;
use Yii;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\v1\traits\GethClientTrait;
use app\modules\v1\traits\JsonParserTrait;

/**
 * This is the model class for table "identity".
 *
 * @property int $id
 * @property string $identity
 * @property string $fullName
 * @property string $url
 * @property string $hash
 * @property string $public_address
 * @property string $recovery_address
 * @property int $created_at
 * @property int $is_valid
 * @property int $is_signed
 * @property int random_number
 * @property int $valid_start_date
 * @property int $valid_end_date
 * @property string display_name
 */
class Identity extends ActiveRecord
{
    use JsonParserTrait;

    public static $identityRegexp = '/^[-a-z0-9]+$/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'identity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity', 'public_address', 'recovery_address'], 'required'],
            [['hash'], 'string'],
            [
                ['created_at', 'is_valid', 'is_signed', 'valid_start_date', 'valid_end_date', 'random_number'],
                'integer'
            ],
            [
                [
                    'identity',
                    'fullName',
                    'url',
                    'public_address',
                    'recovery_address',
                    'display_name'
                ],
                'string',
                'max' => 255
            ],
            ['identity', 'match', 'pattern' => static::$identityRegexp]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identity' => 'Identity',
            'fullName' => 'Full Name',
            'url' => 'Url',
            'hash' => 'Hash',
            'created_at' => 'Created At',
            'is_valid' => 'Is Valid',
            'is_signed' => 'Is Signed',
            'valid_start_date' => 'Valid Start Date',
            'valid_end_date' => 'Valid End Date',
            'recovery_address' => 'Recovery address',
            'public address' => 'Public address',
            'random_number' => 'Random number',
            'display_name' => 'Display Name'
        ];
    }

    public function getTemplate()
    {
        $array = [
             "@context" => "https://w3id.org/did/v1",
             "id" => "did:vb:{$this->identity}",
             "publicKey" => [
                 [
                     "id" => "did:vb:{$this->identity}#keys-1",
                     "type" => "ECDSA-over-secp256k1",
                     "owner" => "did:vb:{$this->identity}",
                     "publicAddress" => $this->public_address
                ],
                [
                     "id" => "did:vb:{$this->identity}#keys-2",
                     "type" => "ECDSA-over-secp256k1",
                     "owner" => "did:vb:{$this->identity}",
                     "publicAddress" => $this->recovery_address
                ]
             ],
             "authentication" => [
                 [
                     "type" => "ECDSA-over-secp256k1",
                     "publicKey" => "did:vb:{$this->identity}#keys-1"
                 ],
                 [
                     "type" => "ECDSA-over-secp256k1",
                     "publicKey" => "did:vb:{$this->identity}#keys-2"
                 ]
             ]
        ];

        return Json::encode($array);
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
            ]
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['slug' => 'identity']);
    }

    public function revokeKeys()
    {
        $model = new IdentityKeysHistory();
        $model->identity_id = $this->id;
        $model->public_address = $this->public_address;
        $model->recovery_address = $this->recovery_address;
        $model->is_revoked = 1;
        $model->save();

        $this->updateAttributes([
            'public_address' => '',
            'recovery_address' => ''
        ]);
    }

    public function getStatements()
    {
        return $this->hasMany(IdentityStatement::className(), ['identity' => 'identity']);
    }

    public function getPurposedKeys()
    {
        return $this->hasMany(IdentityPurposedKeys::className(), ['identity' => 'identity']);
    }

    public function setProperties()
    {
        $this->signature = $this->getSignatureFromMessage();
        $this->address = $this->getPublicAddressFromMessage();
    }

    public static function isOwnerOfAddress($identity, $address)
    {
        $model = self::find()->where(['public_address' => $address])->one();
        if($model !== null) {
            if($model->identity === $identity)
                return true;
        }

        $model = IdentityPurposedKeys::find()->where(['public_address' => $address]);
        if($model !== null) {
            if($model->identity === $identity)
                return true;
        }

        return false;
    }
}
