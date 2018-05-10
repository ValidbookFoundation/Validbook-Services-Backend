<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "story".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $visibility_type
 * @property integer $in_storyline
 * @property integer $in_channels
 * @property integer $in_book
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property StoryBook[] $storyBooks
 * @property StoryImage[] $storyImages
 * @property StorySettings $storySettings
 * @property StoryVideo[] $storyVideos
 */
class Story extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'description', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'visibility_type', 'in_storyline', 'in_channels', 'in_book', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'description' => 'Description',
            'visibility_type' => 'Visibility Type',
            'in_storyline' => 'In Storyline',
            'in_channels' => 'In Channels',
            'in_book' => 'In Book',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoryBooks()
    {
        return $this->hasMany(StoryBook::className(), ['story_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoryImages()
    {
        return $this->hasMany(StoryImage::className(), ['story_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorySettings()
    {
        return $this->hasOne(StorySettings::className(), ['story_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoryVideos()
    {
        return $this->hasMany(StoryVideo::className(), ['story_id' => 'id']);
    }
}
