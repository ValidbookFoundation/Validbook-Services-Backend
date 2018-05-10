<?php

namespace app\modules\v1\models\identity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "identity_keys_history".
 *
 * @property int $id
 * @property int $identity_id
 * @property string $public_address
 * @property string $recovery_address
 * @property int $created_at
 * @property int $is_revoked
 */
class IdentityKeysHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'identity_keys_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity_id', 'public_address', 'recovery_address'], 'required'],
            [['identity_id', 'created_at', 'is_revoked'], 'integer'],
            [['public_address', 'recovery_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identity_id' => 'Identity ID',
            'public_address' => 'Public Address',
            'recovery_address' => 'Recovery Address',
            'created_at' => 'Created At',
            'is_revoked' => 'Is Revoked'
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
}
