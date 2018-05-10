<?php

namespace app\modules\v1\models\identity;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "identity_purposed_keys".
 *
 * @property int $id
 * @property string $identity
 * @property string $public_address
 * @property string $purpose
 * @property int $is_revoked
 * @property int $created_at
 * @property int $updated_at
 */
class IdentityPurposedKeys extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'identity_purposed_keys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity'], 'required'],
            [['purpose'], 'string'],
            [['is_revoked', 'created_at', 'updated_at'], 'integer'],
            [['identity', 'public_address'], 'string', 'max' => 255],
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
            'public_address' => 'Public Address',
            'purpose' => 'Purpose',
            'is_revoked' => 'Is Revoked',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ]
        ];
    }
}
