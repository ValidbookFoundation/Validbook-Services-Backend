<?php

namespace app\modules\v1\models\identity;

use app\modules\v1\models\Aws;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\v1\traits\JsonParserTrait;

/**
 * This is the model class for table "identity_statement".
 *
 * @property int $id
 * @property string $identity
 * @property int $identity_id
 * @property int $statement_url
 * @property int $type
 * @property string $title
 * @property string $hash
 * @property int $created_at
 * @property int $is_revoked
 * @property string $uuid
 *
 * @property Identity $entity
 */
class IdentityStatement extends ActiveRecord
{
    use JsonParserTrait;

    const TYPE_UNIQUE_HUMAN_IDENTITY = 1;

    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'identity_statement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity', 'hash', 'uuid'], 'required'],
            [['created_at', 'is_revoked', 'type', 'identity_id'], 'integer'],
            [['hash'], 'string'],
            ['uuid', 'safe'],
            [['identity', 'title', 'statement_url'], 'string', 'max' => 255],
            [
                ['identity'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Identity::className(), 'targetAttribute' => ['identity' => 'identity']
            ],
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
            'identity_id' => 'Identity id',
            'statement_url' => 'Statement Url',
            'title' => 'Title',
            'hash' => 'Hash',
            'created_at' => 'Created At',
            'is_revoked' => 'Is Revoked',
            'uuid' => 'Uuid',
            'type' => 'Type'
        ];
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

    public function beforeSave($insert)
    {
        $this->upload();

        return parent::beforeSave($insert);
    }

    public function setProperties()
    {
        $this->uuid = $this->getUuid();
        $this->title = $this->getTitleFromMessage();
        $this->hash = $this->hashMessage($this->json);
        $this->signature = $this->getSignatureFromMessage();
        $this->address = $this->getPublicAddressFromMessage();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Identity::className(), ['identity' => 'identity']);
    }

    private function upload()
    {
        $user = Yii::$app->user->identity;
        //if userId is null (for book cover, story images)
        $awsPath = $user->id . '/identityStatements/' . $this->uuid . '.json';

        $this->statement_url = Aws::getAwsUrl($awsPath, $this->json);
    }
}
