<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;


use yii\db\ActiveRecord;

class DimPermissionStory extends ActiveRecord
{
    const CAN_SEE_CONTENT = 'can_see_content';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dim_permission_story';
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