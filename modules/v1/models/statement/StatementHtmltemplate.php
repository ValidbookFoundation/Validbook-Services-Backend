<?php

namespace app\modules\v1\models\statement;

use Yii;

/**
 * This is the model class for table "statement_htmltemplate".
 *
 * @property int $id
 * @property string $title
 * @property string $link
 * @property int $default
 * @property int $sort
 *
 * @property StatementHtmlToJson[] $statementHtmlToJsons
 */
class StatementHtmltemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statement_htmltemplate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['default', 'sort'], 'integer'],
            [['title', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'default' => 'Default',
            'sort' => 'Sort',
            'link' => 'Link'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatementHtmlToJsons()
    {
        return $this->hasMany(StatementHtmlToJson::className(), ['html_id' => 'id']);
    }
}
