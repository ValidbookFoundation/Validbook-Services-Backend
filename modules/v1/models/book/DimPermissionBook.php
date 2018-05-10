<?php
/**
 * Created by PhpStorm.
 * User: our
 * Date: 31.07.17
 * Time: 11:52
 */
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */


namespace app\modules\v1\models\book;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "dim_permission_book".
 *
 * @property integer $id
 * @property string $name
 *
 */
class DimPermissionBook extends ActiveRecord
{
    const CAN_SEE_EXISTS = 'can_see_exists';
    const CAN_SEE_CONTENT = 'can_see_content';
    const CAN_ADD_STORIES = 'can_add_stories';
    const CAN_DELETE_STORIES = 'can_delete_stories';
    const CAN_MANAGE_SETTINGS = 'can_manage_settings';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dim_permission_book';
    }

    public static function getId($name)
    {
        $model = self::findOne(['name' => $name]);
        return $model->id;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }
}