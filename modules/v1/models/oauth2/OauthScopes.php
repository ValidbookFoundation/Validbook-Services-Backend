<?php

namespace app\modules\v1\models\oauth2;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "oauth_scopes".
 *
 * @property string $scope
 * @property integer $is_default
 */
class OauthScopes extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_scopes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scope', 'is_default'], 'required'],
            [['is_default'], 'integer'],
            [['scope'], 'string', 'max' => 2000]
        ];
    }
}