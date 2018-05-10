<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;


use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "story_files".
 *
 * @property integer $id
 * @property integer $story_id
 * @property string $type
 * @property string $url
 * @property string $etag
 * @property integer $created_at
 */
class StoryFiles extends ActiveRecord
{
    const STORY_ENTITY = 'story';


    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_files';
    }

    public static function getStoryImages($id)
    {
        $images = self::find()->where([
            'story_id' => $id,
            'type' => ['image/jpeg', 'image/png']
        ])->all();
        $data = [];

        /** @var StoryFiles $image */
        foreach ($images as $image) {
            $data[] = $image->getFormattedCard();
        }
        return $data;
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['story_id', 'type', 'url'], 'required'],
            [['story_id', 'created_at'], 'integer'],
            [['type', 'url', 'etag'], 'string', 'max' => 255],
        ];
    }


    public function getFormattedCard()
    {
        $pictureSmall = $thumbSize = null;

        if ($this->type == 'image/jpeg' || $this->type == 'image/png') {
            $storyImage = StoryImageSize::findOne(['type' => 'thumbnail', 'original_id' => $this->id]);
            $pictureSmall = $storyImage->url ?? null;
            $thumbSize = $storyImage->size ?? null;
        }

        return [
            'id' => $this->id,
            'url' => $this->url,
            'story_id' => $this->story_id,
            'type' => $this->type,
            'picture_original' => $this->url,
            'picture_small' => $pictureSmall,
            'thumbnail_size' => $thumbSize,
            'created' => Yii::$app->formatter->asDate($this->created_at)
        ];
    }

    public function getAllImages($storyIds)
    {
        $sPhotos = [];
        $this->setItemsPerPage(20);

        $storiesImagesOriginal = self::find()
            ->where(['story_id' => $storyIds, 'type' => ['image/jpeg', 'image/png']])
            ->orderBy('created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var StoryFiles $sPhoto */
        foreach ($storiesImagesOriginal as $sPhoto) {

            /** @var StoryImageSize $storyImage */
            $storyImage = StoryImageSize::find()
                ->where(['type' => 'small', 'original_id' => $sPhoto->id])
                ->one();

            $smallPicture = $storyImage->url ?? null;

            $sPhotos[] = [
                'id' => $sPhoto->id,
                'entity' => self::STORY_ENTITY,
                'entity_id' => $sPhoto->story_id,
                'picture_original' => $sPhoto->url,
                'picture_small' => $smallPicture,
                'created' => Yii::$app->formatter->asDate($sPhoto->created_at)
            ];
        }

        return $sPhotos;
    }
}