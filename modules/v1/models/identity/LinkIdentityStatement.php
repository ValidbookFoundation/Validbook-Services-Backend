<?php

namespace app\modules\v1\models\identity;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\v1\traits\JsonParserTrait;
use Yii;

/**
 * This is the model class for table "link_identity_statement".
 *
 * @property int $id
 * @property string $identity
 * @property int $identity_id
 * @property string $identity_statement_uuid
 * @property int $identity_statement_id
 * @property int $is_ignored
 * @property string $url
 * @property string $hash
 * @property int $is_revoked
 * @property int $created_at
 */
class LinkIdentityStatement extends ActiveRecord
{
    use JsonParserTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link_identity_statement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity_statement_id', 'is_revoked', 'created_at', 'is_ignored', 'identity_id'], 'integer'],
            [['hash'], 'string'],
            [['owner_identity', 'identity_statement_uuid', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_identity' => 'Owner Identity',
            'identity_statement_uuid' => 'Identity Statement Uuid',
            'identity_statement_id' => 'Identity Statement ID',
            'url' => 'Url',
            'hash' => 'Hash',
            'is_revoked' => 'Is Revoked',
            'created_at' => 'Created At',
            'is_ignored' => 'Is ignored',
            'identity_id' => 'Identity id'
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

    public function getIdentityStatement()
    {
        return $this->hasOne(IdentityStatement::className(), ['id' => 'identity_statement_id']);
    }
}
