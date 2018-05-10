<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "cover_size".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $model_id
 * @property integer $original_id
 * @property integer $is_actual
 * @property string $size
 * @property string $url
 */
class Cover extends ActiveRecord
{
    const BOOK_TYPE = 1;
    const USER_TYPE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cover_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'model_id', 'size', 'url'], 'required'],
            [['type', 'model_id', 'is_actual', 'original_id'], 'integer'],
            [['size'], 'string', 'max' => 25],
            [['url'], 'string', 'max' => 255],
        ];
    }

    public function getUrl()
    {
        return $this->url;
    }

}
