<?php

namespace backend\models;

use Yii;
use amnah\yii2\user\models\User as BaseUser;

/**
 * This is the model class for table "tbl_user".
 *
 * @property string $id
 * @property string $role_id
 * @property integer $status
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $logged_in_ip
 * @property string $logged_in_at
 * @property string $created_ip
 * @property string $created_at
 * @property string $updated_at
 * @property string $banned_at
 * @property string $banned_reason
 *
 * @property Profile $profile
 * @property Role $role
 * @property UserToken[] $userTokens
 */
class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            // password rules
            [['newPassword'], 'string', 'min' => 3],
            [['newPassword'], 'filter', 'filter' => 'trim'],
            [['newPassword'], 'required', 'on' => ['register', 'reset']],
            [['newPasswordConfirm'], 'required', 'on' => ['reset']],
            [['newPasswordConfirm'], 'compare', 'compareAttribute' => 'newPassword', 'message' => Yii::t('user', 'Passwords do not match')],

            // account page
            [['currentPassword'], 'validateCurrentPassword', 'on' => ['account']],

            // admin crud rules
            [['role_id', 'status'], 'required', 'on' => ['admin']],
            [['role_id', 'status'], 'integer', 'on' => ['admin']],
            [['banned_at'], 'integer', 'on' => ['admin']],
            [['banned_reason'], 'string', 'max' => 255, 'on' => 'admin'],
        ];

        // add required for currentPassword on account page
        // only if $this->password is set (might be null from a social login)
        if ($this->password) {
            $rules[] = [['currentPassword'], 'required', 'on' => ['account']];
        }

        // add required rules for email depending on module properties
        if ($this->module->requireEmail) {
            $rules[] = ["email", "required"];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'role_id' => Yii::t('user', 'Role ID'),
            'status' => Yii::t('user', 'Status'),
            'email' => Yii::t('user', 'Email'),
            'password' => Yii::t('user', 'Password'),
            'auth_key' => Yii::t('user', 'Auth Key'),
            'access_token' => Yii::t('user', 'Access Token'),
            'logged_in_ip' => Yii::t('user', 'Logged In Ip'),
            'logged_in_at' => Yii::t('user', 'Logged In At'),
            'created_ip' => Yii::t('user', 'Created Ip'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
            'banned_at' => Yii::t('user', 'Banned At'),
            'banned_reason' => Yii::t('user', 'Banned Reason'),

            // virtual attributes set above
            'currentPassword' => Yii::t('user', 'Current Password'),
            'newPassword' => $this->isNewRecord ? Yii::t('user', 'Password') : Yii::t('user', 'New Password'),
            'newPasswordConfirm' => Yii::t('user', 'New Password Confirm'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function ($event) {
                    return gmdate("Y-m-d H:i:s");
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        // check if we're setting $this->password directly
        // handle it by setting $this->newPassword instead
        $dirtyAttributes = $this->getDirtyAttributes();
        if (isset($dirtyAttributes["password"])) {
            $this->newPassword = $dirtyAttributes["password"];
        }

        // hash new password if set
        if ($this->newPassword) {
            $this->password = Yii::$app->security->generatePasswordHash($this->newPassword, 12);
        }

        // convert banned_at checkbox to date
        if ($this->banned_at) {
            $this->banned_at = gmdate("Y-m-d H:i:s");
        }

        // ensure fields are null so they won't get set as empty string
        $nullAttributes = ["email", "banned_at", "banned_reason"];
        foreach ($nullAttributes as $nullAttribute) {
            $this->$nullAttribute = $this->$nullAttribute ? $this->$nullAttribute : null;
        }

        return parent::beforeSave($insert);
    }

}