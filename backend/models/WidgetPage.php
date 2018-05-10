<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "widget_page".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $title
 * @property string $story
 *
 * @property Staticpage $page
 */
class WidgetPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'widget_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['story'], 'required'],
            [['page_id'], 'integer'],
            [['story'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staticpage::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'title' => 'Title',
            'story' => 'Story',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Staticpage::className(), ['id' => 'page_id']);
    }
}
