<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;

use app\modules\v1\traits\GethClientTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_file".
 *
 * @property integer $id
 * @property integer $doc_id
 * @property string $title
 * @property string $type
 * @property string $url
 * @property string $hash
 * @property integer $created_at
 */
class DocumentFile extends ActiveRecord
{

    use GethClientTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_file';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'url', 'hash', 'doc_id'], 'required'],
            [['hash'], 'string'],
            [['created_at', 'doc_id'], 'integer'],
            [['title', 'type', 'url'], 'string', 'max' => 255],
        ];
    }

    public function deleteFromBucket()
    {
        $s3 = Yii::$app->get('s3');

        $awsPath = explode("/", $this->url);
        $awsPath = array_slice($awsPath, 4);
        $awsPath = implode("/", $awsPath);

        $exist = $s3->exist($awsPath);

        if ($exist) {
            $s3->commands()->delete($awsPath)->execute();
            return true;
        }

        return false;
    }

}
