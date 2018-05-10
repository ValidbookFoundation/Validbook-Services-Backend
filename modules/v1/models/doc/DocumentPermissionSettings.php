<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "doc_permission_settings".
 *
 * @property integer $id
 * @property integer $doc_id
 * @property integer $permission_id
 * @property integer $permission_state
 * @property integer $custom_permission_id
 */
class DocumentPermissionSettings extends ActiveRecord
{
    const PRIVACY_TYPE_PRIVATE = 0;
    const PRIVACY_TYPE_PUBLIC = 1;
    const PRIVACY_TYPE_CUSTOM = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_permission_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_id', 'permission_id', 'permission_state'], 'required'],
            [['doc_id', 'permission_id', 'permission_state', 'custom_permission_id'], 'integer'],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['doc_id' => 'id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => DimPermissionDocument::className(), 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }


    public static function getSettings()
    {
        $settings = [
            'type_id' => [
                'can_see_content' => Yii::$app->request->post(
                    'visibility',
                    self::PRIVACY_TYPE_PRIVATE
                ),
                'can_sign' => Yii::$app->request->post(
                    'visibility',
                    self::PRIVACY_TYPE_PRIVATE
                ),
            ],
            'user_array' => [
                'can_see_content' => Yii::$app->request->post('users_can_see_content', []),
                'can_sign' => Yii::$app->request->post('users_can_sign', []),
            ]
        ];

        return $settings;
    }

    public static function setValues($docId)
    {
        $settings = self::getSettings();

        $countSavedModels = 0;

        foreach ($settings['type_id'] as $key => $value) {
            $model = new self();

            if ($value == self::PRIVACY_TYPE_CUSTOM) {
                if (!empty($settings['user_array'][$key]) && is_array($settings['user_array'][$key])) {
                    $customId = DocumentCustomPermissions::setValues($settings['user_array'][$key]);
                    $model->custom_permission_id = $customId;
                }
            }
            $model->doc_id = $docId;
            $model->permission_id = DimPermissionDocument::getId($key);
            $model->permission_state = $value;

            $model->save();
            $countSavedModels++;

        }
        if ($countSavedModels == 2) {
            return true;
        }
        return false;
    }

    public static function updateValues($docId)
    {
        $docPermissions = self::find()->where(['doc_id' => $docId])->all();
        $docSettings = self::getSettings();
        $i = 0;
        foreach ($docSettings['type_id'] as $key => $value) {
            self::updateSetting($key, $value, $docPermissions[$i++], $docSettings['user_array']);
        }
    }

    private static function updateSetting($key, $value, self $model, array $usersArray)
    {
        if ($value == self::PRIVACY_TYPE_CUSTOM) {
            if (!empty($usersArray[$key])) {
                $custom = (new Query())
                    ->select('custom_id')
                    ->from('story_custom_permissions')
                    ->where(['custom_id' => $model->custom_permission_id])
                    ->one();

                if (!empty($custom)) {
                    $customId = ArrayHelper::getValue($custom, 'custom_id');

                    $customId = DocumentCustomPermissions::setValues($usersArray[$key], $customId);
                } else {
                    $customId = DocumentCustomPermissions::setValues($usersArray[$key]);
                }
                $model->custom_permission_id = $customId;
                $model->permission_state = $value;
                $model->update();
            }
        } else {
            $model->permission_state = $value;
            if (!empty($model->custom_permission_id)) {
                DocumentCustomPermissions::deleteAll(['custom_id' => $model->custom_permission_id]);
            }
            $model->custom_permission_id = null;
            $model->update();
        }

    }

    public static function mapSettings()
    {
        $models = DimPermissionDocument::find()->all();
        $map = ArrayHelper::getColumn(ArrayHelper::index($models, 'id'), 'name');
        return $map;
    }

    public static function optionsForAccessSettings()
    {
        return [
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
                'name' => 'can_sign',
                'values' => [
                    self::PRIVACY_TYPE_PUBLIC => "anyone",
                    self::PRIVACY_TYPE_PRIVATE => "only you",
                    self::PRIVACY_TYPE_CUSTOM => "specific people"
                ],
                'customFieldName' => 'users_can_sign'
            ]
        ];
    }
}