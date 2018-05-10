<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;

use app\modules\v1\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notification_settings".
 *
 * @property integer $user_id
 * @property string $settings
 *
 * @property User $user
 */
class NotificationSettings extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settings', 'user_id'], 'required'],
            [['settings'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'settings' => 'Settings',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getDefault()
    {
        $settings = [
            'settings' => [
                [
                    'label' => 'When someone followed me',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone followed my book',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone commented on my story',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone liked my story',
                    'email' => false,
                    'web' => true
                ],
                [
                    'label' => 'When someone commented on story I commented',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone replied to my comment',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone commented on story I liked',
                    'email' => false,
                    'web' => true
                ],
                [
                    'label' => 'When someone liked comment I wrote',
                    'email' => false,
                    'web' => true
                ],
                [
                    'label' => 'When someone knocked on my book',
                    'email' => false,
                    'web' => true
                ],
                [
                    'label' => 'When someone sent me private message',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone sent me token',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone validated my Human Card',
                    'email' => true,
                    'web' => true
                ],
                [
                    'label' => 'When someone validated my Human Card, after I validated their Human Card',
                    'email' => true,
                    'web' => true
                ]
            ],
            'updates' => [
                [
                    'label' => 'News about Validbook and feature updates',
                    'value' => true,
                ],
                [
                    'label' => 'Tips on getting more from Validbook',
                    'value' => true
                ],
                [
                    'label' => 'Things happened on Validbook last week',
                    'email' => true
                ],
            ]
        ];

        return $settings;
    }
}
