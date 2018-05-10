<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "book_permission_settings".
 *
 * @property integer $id
 * @property integer $story_id
 * @property integer $permission_id
 * @property integer $permission_state
 * @property integer $custom_permission_id
 */
class StoryPermissionSettings extends ActiveRecord
{
    const PRIVACY_TYPE_PRIVATE = 0;
    const PRIVACY_TYPE_PUBLIC = 1;
    const PRIVACY_TYPE_CUSTOM = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_permission_settings';
    }

    public static function getBooksVisibility($bookIds)
    {
        $permissionStates = (new Query())
            ->select('book_id, permission_state, custom_permission_id')
            ->from('book_permission_settings')
            ->where(['book_id' => $bookIds, 'permission_id' => 2])
            ->all();
        $permissionStates = ArrayHelper::getColumn($permissionStates, 'permission_state');
        $customIds = ArrayHelper::getColumn($permissionStates, 'custom_permission_id');
        if (in_array(1, $permissionStates)) {
            return [
                "value" => 1,
                "users_custom_visibility" => []
            ];
        }
        if (in_array(2, $permissionStates)) {
            $customUsersIds = (new Query())
                ->select('user_id')
                ->from('book_custom_permission')
                ->where(['custom_id' => $customIds])
                ->all();
            return [
                "value" => 2,
                "users_custom_visibility" => array_unique(ArrayHelper::getColumn($customUsersIds, 'user_id'))
            ];
        }
        return [
            "value" => 0,
            "users_custom_visibility" => []
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['story_id', 'permission_id', 'permission_state'], 'required'],
            [['story_id', 'permission_id', 'permission_state', 'custom_permission_id'], 'integer'],
            [['story_id'], 'exist', 'skipOnError' => true, 'targetClass' => Story::className(), 'targetAttribute' => ['story_id' => 'id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => DimPermissionStory::className(), 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }


    public static function getSettings()
    {
        $settings = [
            'type_id' => [
                'can_see_content' => Yii::$app->request->post(
                    'visibility',
                    self::PRIVACY_TYPE_PUBLIC
                ),
            ],
            'user_array' => [
                'can_see_content' => Yii::$app->request->post('users_custom_visibility', []),
            ]
        ];

        return $settings;
    }

    public static function setValues($storyId)
    {
        $settings = self::getSettings();

        $countSavedModels = 0;

        foreach ($settings['type_id'] as $key => $value) {
            $model = new self();

            if ($value == self::PRIVACY_TYPE_CUSTOM) {
                if (!empty($settings['user_array'][$key]) && is_array($settings['user_array'][$key])) {
                    $customId = StoryCustomPermissions::setValues($settings['user_array'][$key]);
                    $model->custom_permission_id = $customId;
                }
            }
            $model->story_id = $storyId;
            $model->permission_id = DimPermissionStory::getId($key);
            $model->permission_state = $value;

            $model->save();
            $countSavedModels++;

        }
        if ($countSavedModels == 1) {
            return true;
        }
        return false;
    }

    public static function updateValues($storyId)
    {
        $storyPermissions = self::find()->where(['story_id' => $storyId])->all();
        $storySettings = self::getSettings();
        $i = 0;
        foreach ($storySettings['type_id'] as $key => $value) {
            self::updateSetting($key, $value, $storyPermissions[$i++], $storySettings['user_array']);
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

                    $customId = StoryCustomPermissions::setValues($usersArray[$key], $customId);
                } else {
                    $customId = StoryCustomPermissions::setValues($usersArray[$key]);
                }
                $model->custom_permission_id = $customId;
                $model->permission_state = $value;
                $model->update();
            }
        } else {
            $model->permission_state = $value;
            if (!empty($model->custom_permission_id)) {
                StoryCustomPermissions::deleteAll(['custom_id' => $model->custom_permission_id]);
            }
            $model->custom_permission_id = null;
            $model->update();
        }

    }

    public static function mapSettings()
    {
        $models = DimPermissionStory::find()->all();
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
            ]
        ];
    }

    public function getPermissionName(){
        if($this->permission_state === self::PRIVACY_TYPE_PRIVATE){
            return 'private';
        }elseif ($this->permission_state === self::PRIVACY_TYPE_PUBLIC){
            return 'public';
        }elseif($this->permission_state === self::PRIVACY_TYPE_CUSTOM){
            return 'custom';
        }
    }
}