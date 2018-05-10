<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\book;

use app\modules\v1\jobs\UpdateBookForChannelJob;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "book_permission_settings".
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $permission_id
 * @property integer $permission_state
 * @property integer $custom_permission_id
 */
class BookPermissionSettings extends ActiveRecord
{
    const PRIVACY_TYPE_PRIVATE = 0;
    const PRIVACY_TYPE_PUBLIC = 1;
    const PRIVACY_TYPE_CUSTOM = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book_permission_settings';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'permission_id', 'permission_state'], 'required'],
            [['book_id', 'permission_id', 'permission_state', 'custom_permission_id'], 'integer'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => DimPermissionBook::className(), 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'permission_id' => 'Permission ID',
            'permission_state' => 'Permission State',
            'custom_permission_id' => 'Custom Permission ID',
        ];
    }

    public static function getSettings()
    {
        $settings = [
            'type_id' => [
                'can_see_exists' => Yii::$app->request->post(
                    'can_see_exists',
                    self::PRIVACY_TYPE_PUBLIC
                ),
                'can_see_content' => Yii::$app->request->post(
                    'can_see_content',
                    self::PRIVACY_TYPE_PUBLIC
                ),
                'can_add_stories' => Yii::$app->request->post(
                    'can_add_stories',
                    self::PRIVACY_TYPE_PRIVATE
                ),
                'can_delete_stories' => Yii::$app->request->post(
                    'can_delete_stories',
                    self::PRIVACY_TYPE_PRIVATE
                ),
                'can_manage_settings' => Yii::$app->request->post(
                    'can_manage_settings',
                    self::PRIVACY_TYPE_PRIVATE
                ),
            ],
            'user_array' => [
                'can_see_exists' => Yii::$app->request->post('users_can_see_exists', []),
                'can_see_content' => Yii::$app->request->post('users_can_see_content', []),
                'can_add_stories' => Yii::$app->request->post('users_can_add_stories', []),
                'can_delete_stories' => Yii::$app->request->post('users_can_delete_stories', []),
                'can_manage_settings' => Yii::$app->request->post('users_can_manage_settings', [])
            ]
        ];

        return $settings;
    }

    public static function setValues($bookId)
    {
        $settings = self::getSettings();

        $countSavedModels = 0;

        foreach ($settings['type_id'] as $key => $value) {
            $model = new self();

            if ($value == self::PRIVACY_TYPE_CUSTOM) {
                if (!empty($settings['user_array'][$key]) && is_array($settings['user_array'][$key])) {
                    $customId = BookCustomPermissions::setValues($settings['user_array'][$key]);
                    $model->custom_permission_id = $customId;
                }
            }
            $model->book_id = $bookId;
            $model->permission_id = DimPermissionBook::getId($key);
            $model->permission_state = $value;

            $model->save();
            $countSavedModels++;

        }
        if ($countSavedModels == 5) {
            return true;
        }
        return false;
    }

    public static function updateValues($bookId)
    {
        $bookPermissions = ArrayHelper::index(self::find()->where(['book_id' => $bookId])->all(), 'permission_id');
        $postSettings = self::getSettings();

        foreach ($postSettings['type_id'] as $key => $value) {
            $dimSetting = DimPermissionBook::findOne(['name' => $key]);
            self::updateSetting($key, $value, $bookPermissions[$dimSetting->id], $postSettings['user_array']);
        }
    }

    private static function updateSetting($key, $value, self $model, array $usersArray)
    {
        if ($value == self::PRIVACY_TYPE_CUSTOM) {
            if (!empty($usersArray[$key])) {
                $custom = (new Query())
                    ->from('book_custom_permissions')
                    ->where(['custom_id' => $model->custom_permission_id])
                    ->one();

                if (!empty($custom)) {
                    $customId = ArrayHelper::getValue($custom, 'custom_id');

                    $customId = BookCustomPermissions::setValues($usersArray[$key], $customId);
                } else {
                    $customId = BookCustomPermissions::setValues($usersArray[$key]);
                }
                $model->custom_permission_id = $customId;
                $model->permission_state = $value;
                $model->update();
            }
        } else {
            $model->permission_state = $value;
            if (!empty($model->custom_permission_id)) {
                BookCustomPermissions::deleteAll(['custom_id' => $model->custom_permission_id]);
            }
            $model->custom_permission_id = null;
            $model->update();
        }

        if ($model->permission_id == 2) {

            Yii::$app->queue->push(new UpdateBookForChannelJob([
                'bookId' => $model->book_id,
                'customPermissionId' => $model->custom_permission_id,
                'permissionState' => $model->permission_state
            ]));
        }
    }

    public static function mapSettings()
    {
        $models = DimPermissionBook::find()->all();
        $map = ArrayHelper::getColumn(ArrayHelper::index($models, 'id'), 'name');
        return $map;
    }

    public static function optionsForAccessSettings()
    {
        return [
            [
                'name' => 'can_see_exists',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_see_exists'
            ],
            [
                'name' => 'can_see_content',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_see_content'
            ],
            [
                'name' => 'can_add_stories',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_add_stories'
            ],
            [
                'name' => 'can_delete_stories',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_delete_stories'
            ],
            [
                'name' => 'can_manage_settings',
                'values' => [
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_manage_settings'
            ]
        ];
    }
}