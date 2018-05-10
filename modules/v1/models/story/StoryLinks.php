<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;

use app\modules\v1\models\story\StoryVideo;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "story_links".
 *
 * @property integer $id
 * @property integer $story_id
 * @property integer $video_id
 * @property integer $image_id
 * @property string $link
 * @property string $title
 * @property string $description
 * @property string $twitter_author
 * @property string $twitter_avatar
 * @property integer $created_at
 *
 * @property StoryFiles $image
 * @property Story $story
 * @property StoryVideo $video
 */
class StoryLinks extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['story_id', 'video_id', 'image_id', 'link', 'title', 'description', 'twitter_author', 'twitter_avatar', 'created_at'], 'required'],
            [['story_id', 'video_id', 'image_id', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['link', 'title', 'twitter_author', 'twitter_avatar'], 'string', 'max' => 255],
            [['story_id'], 'exist', 'skipOnError' => true, 'targetClass' => Story::className(), 'targetAttribute' => ['story_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'story_id' => 'Story ID',
            'video_id' => 'Video ID',
            'image_id' => 'Image ID',
            'link' => 'Link',
            'title' => 'Title',
            'description' => 'Description',
            'twitter_author' => 'Twitter Author',
            'twitter_avatar' => 'Twitter Avatar',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(StoryFiles::className(), ['id' => 'image_id', 'type' => ['image/jpeg', 'image/png']]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStory()
    {
        return $this->hasOne(Story::className(), ['id' => 'story_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(StoryVideo::className(), ['id' => 'video_id']);
    }

    public static function getStoryLinks($id)
    {
        $links = self::find()->where([
            'story_id' => $id,
        ])->all();
        $data = [];

        if (!empty($links)) {
            /** @var StoryLinks $link */
            foreach ($links as $link) {
                $data[] = [
                    "id" => $link->id,
                    "link" => $link->link,
                    "title" => $link->title,
               //     "image_url" => $link->image->url,
                    "description" => $link->description,
                    "twitter_author" => $link->twitter_author,
                    "twitter_avatar" => $link->twitter_avatar
                ];
            }
        }

        return $data;

    }
}
