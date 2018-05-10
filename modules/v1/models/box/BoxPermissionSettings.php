<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\box;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "box_permission_settings".
 *
 * @property integer $id
 * @property integer $box_id
 * @property integer $permission_id
 * @property integer $permission_state
 * @property integer $custom_permission_id
 */
class BoxPermissionSettings extends ActiveRecord
{
    const PRIVACY_TYPE_PRIVATE = 0;
    const PRIVACY_TYPE_PUBLIC = 1;
    const PRIVACY_TYPE_CUSTOM = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'box_permission_settings';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['box_id', 'permission_id', 'permission_state'], 'required'],
            [['box_id', 'permission_id', 'permission_state', 'custom_permission_id'], 'integer'],
            [['box_id'], 'exist', 'skipOnError' => true, 'targetClass' => Box::className(), 'targetAttribute' => ['box_id' => 'id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => DimPermissionBox::className(), 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }


    public static function getSettings()
    {
        $settings = [
            'type_id' => [
                'can_see_exists' => Yii::$app->request->post(
                    'can_see_exists',
                    self::PRIVACY_TYPE_PRIVATE
                ),
                'can_see_content' => Yii::$app->request->post(
                    'can_see_content',
                    self::PRIVACY_TYPE_PRIVATE
                ),
                'can_add_documents' => Yii::$app->request->post(
                    'can_add_stories',
                    self::PRIVACY_TYPE_PRIVATE
                ),
                'can_delete_documents' => Yii::$app->request->post(
                    'can_delete_stories',
                    self::PRIVACY_TYPE_PRIVATE
                ),
            ],
            'user_array' => [
                'can_see_exists' => Yii::$app->request->post('users_can_see_exists', []),
                'can_see_content' => Yii::$app->request->post('users_can_see_content', []),
                'can_add_documents' => Yii::$app->request->post('users_can_add_documents', []),
                'can_delete_documents' => Yii::$app->request->post('users_can_delete_documents', []),
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
                    $customId = BoxCustomPermissions::setValues($settings['user_array'][$key]);
                    $model->custom_permission_id = $customId;
                }
            }
            $model->box_id = $bookId;
            $model->permission_id = DimPermissionBox::getId($key);
            $model->permission_state = $value;

            $model->save();
            $countSavedModels++;

        }
        if ($countSavedModels == 4) {
            return true;
        }
        return false;
    }

    public static function updateValues($boxId)
    {
        $bookPermissions = ArrayHelper::index(self::find()->where(['box_id' => $boxId])->all(), 'permission_id');
        $postSettings = self::getSettings();

        foreach ($postSettings['type_id'] as $key => $value) {
            $dimSetting = DimPermissionBox::findOne(['name' => $key]);
            self::updateSetting($key, $value, $bookPermissions[$dimSetting->id], $postSettings['user_array']);
        }
    }

    private static function updateSetting($key, $value, self $model, array $usersArray)
    {
        if ($value == self::PRIVACY_TYPE_CUSTOM) {
            if (!empty($usersArray[$key])) {
                $custom = (new Query())
                    ->from('box_custom_permissions')
                    ->where(['custom_id' => $model->custom_permission_id])
                    ->one();

                if (!empty($custom)) {
                    $customId = ArrayHelper::getValue($custom, 'custom_id');

                    $customId = BoxCustomPermissions::setValues($usersArray[$key], $customId);
                } else {
                    $customId = BoxCustomPermissions::setValues($usersArray[$key]);
                }
                $model->custom_permission_id = $customId;
                $model->permission_state = $value;
                $model->update();
            }
        } else {
            $model->permission_state = $value;
            if (!empty($model->custom_permission_id)) {
                BoxCustomPermissions::deleteAll(['custom_id' => $model->custom_permission_id]);
            }
            $model->custom_permission_id = null;
            $model->update();
        }

    }

    public static function mapSettings()
    {
        $models = DimPermissionBox::find()->all();
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
                'name' => 'can_add_documents',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_add_documents'
            ],
            [
                'name' => 'can_delete_documents',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_delete_documents'
            ]
        ];
    }
}