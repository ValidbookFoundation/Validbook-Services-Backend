<?php

namespace app\modules\v1\models\statement;

use app\modules\v1\helpers\JsonVCHelper;
use Yii;

/**
 * This is the model class for table "statement_template".
 *
 * @property int $id
 * @property string $title
 * @property string $json
 * @property int $sort
 * @property ont $will_be_json_changed
 * @property int $default
 *
 * @property Statement[] $statements
 */
class StatementTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statement_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'default', 'will_be_json_changed'], 'integer'],
            [['title'], 'string', 'max' => 255],
            ['json', 'safe']
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
            'sort' => 'Sort',
            'default' => 'Default',
            'json' => 'Json',
            'will_be_json_changed' => 'Will be json changed'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatements()
    {
        return $this->hasMany(Statement::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(
            StatementHtmltemplate::className(),
            ['id' => 'html_id']
        )->viaTable(
            'statement_html_to_json',
            ['json_id' => 'id']
        )->select(['id', 'title', 'link'])->orderBy('sort');
    }

    public static function regenerateJson($json, $templateId) {
        switch ($templateId) {
            case 4 : //template name: "Certificate of File Signing"
                $json = JsonVCHelper::changeIdentityForDescription($json);
                break;

            default :
                break;
        }

        return $json;
    }
}
