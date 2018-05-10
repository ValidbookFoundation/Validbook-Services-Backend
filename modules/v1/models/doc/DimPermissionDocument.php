<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;


use yii\db\ActiveRecord;

class DimPermissionDocument extends ActiveRecord
{
    const CAN_SEE_CONTENT = 'can_see_content';
    const CAN_SIGN = 'can_sign';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dim_permission_document';
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