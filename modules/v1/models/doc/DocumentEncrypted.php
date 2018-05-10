<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */


namespace app\modules\v1\models\doc;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_encrypted".
 *
 * @property integer $id
 * @property integer $document_id
 * @property string $receiver_public_address
 * @property string $url
 */
class DocumentEncrypted extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_encrypted';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'receiver_public_address', 'url'], 'required'],
            [['document_id'], 'integer'],
            [['receiver_public_address', 'url'], 'string', 'max' => 255],
        ];
    }

}
