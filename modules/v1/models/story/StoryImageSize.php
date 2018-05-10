<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;


use app\modules\v1\traits\PaginationTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "story_files".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $original_id
 * @property string $size
 * @property string $type
 * @property string $url
 */
class StoryImageSize extends ActiveRecord
{
    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_image_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'url', 'original_id', 'type', 'size'], 'required'],
            [['model_id', 'original_id'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['size'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 25],
        ];
    }

}