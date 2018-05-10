<?php

namespace app\modules\v1\models\statement;

use Yii;

/**
 * This is the model class for table "statement_html_to_json".
 *
 * @property int $id
 * @property int $html_id
 * @property int $json_id
 *
 * @property StatementHtmltemplate $html
 * @property StatementTemplate $json
 */
class StatementHtmlToJson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statement_html_to_json';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['html_id', 'json_id'], 'integer'],
            [['html_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatementHtmltemplate::className(), 'targetAttribute' => ['html_id' => 'id']],
            [['json_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatementTemplate::className(), 'targetAttribute' => ['json_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'html_id' => 'Html ID',
            'json_id' => 'Json ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHtml()
    {
        return $this->hasOne(StatementHtmltemplate::className(), ['id' => 'html_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJson()
    {
        return $this->hasOne(StatementTemplate::className(), ['id' => 'json_id']);
    }


}
