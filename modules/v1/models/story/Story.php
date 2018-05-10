<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\story;

use app\modules\v1\helpers\StoryHelper;
use app\modules\v1\jobs\BlockedStoryInChannelsAfterLogJob;
use app\modules\v1\models\book\BookCustomPermissions;
use app\modules\v1\models\book\BookPermissionSettings;
use app\modules\v1\models\Comment;
use app\modules\v1\models\Like;
use app\modules\v1\models\search\Search;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "story".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $in_storyline
 * @property integer $in_channels
 * @property integer $in_book
 * @property string $created_at
 * @property string $updated_at
 * @property string $started_on
 * @property string $completed_on
 *
 * @property User $user
 * @property StoryBook[] $storyBooks
 */
class Story extends ActiveRecord implements Search
{

    const STORY_IMAGE_PLACEHOLDER = '$IMAGE_PLACEHOLDER$'; // Usage: $IMAGE_PLACEHOLDER$[853] where 853 is image ID
    const STORY_VIDEO_PLACEHOLDER = '$VIDEO_PLACEHOLDER$'; // Usage: $VIDEO_PLACEHOLDER$[24]  where  24 is video ID

    use PaginationTrait;

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
            [['description', 'in_storyline', 'in_channels', 'in_book'], 'required'],
            [['user_id', 'in_storyline', 'in_channels', 'in_book'], 'integer'],
            ['in_storyline', 'in', 'range' => [0, 1]],
            ['in_channels', 'in', 'range' => [0, 1]],
            ['in_book', 'in', 'range' => [0, 1]],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'started_on', 'completed_on'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['id', 'updated_at']);
                    $model->andWhere(['in_storyline' => 1]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to($model->url, true),
                        'lastmod' => $model->updated_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasOne(StoryPermissionSettings::className(), ['story_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return Yii::$app->params['siteUrl'] . "/story/" . $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getBooks()
    {
        return $this->hasMany(StoryBook::className(), ['story_id' => 'id'])
            ->where(['is_moved_to_bin' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(StoryLinks::className(), ['story_id' => 'id']);
    }

    public static function format($storyModels, $from = 'storyline')
    {
        $data = [];

        /** @var Story $story */
        foreach ($storyModels as $story) {
            if ($story->isVisibleForUser()) {
                //format date response
                $created = Yii::$app->formatter->asDate($story->created_at);
                $exactCreated = Yii::$app->formatter->asDate($story->created_at, "dd MMM yyyy H:mm:ss");
                $startedOn = $story->started_on ? Yii::$app->formatter->asDate($story->started_on) : $created;
                $completedOn = $story->completed_on ? Yii::$app->formatter->asDate($story->completed_on) : null;

                $description = StoryHelper::replacePlaceholdersToTags($story->description);
                $visibilityType = $story->getVisibilityName();
                $customVisibilityUsers = $story->getCustomVisibilityUsers();

                $images = StoryFiles::getStoryImages($story->id);
                $links = StoryLinks::getStoryLinks($story->id);
                $books = StoryBook::getStoryBooks($story->id);
                $likes = Like::getStoryLikes($story);
                $comment = new Comment();
                $comments = $comment->getCommentsForStory($story->id, $story->getPage(), $from);
                $commentCounts = (int)Comment::find()->where(['entity_id' => $story->id, 'parent_id' => 0])->count();
                /*--------------------*/

                $data[] = [
                    "id" => $story->id,
                    "text" => $description,
                    "date" => [
                        "created" => $created,
                        "exactCreated" => $exactCreated,
                        "startedOn" => $startedOn,
                        "completedOn" => $completedOn
                    ],
                    "loudness" => [
                        "inStoryline" => boolval($story->in_storyline),
                        "inChannels" => boolval($story->in_channels),
                        "inBooks" => boolval($story->in_book)
                    ],
                    "visibility" => [
                        "value" => $visibilityType,
                        "users_custom_visibility" => $customVisibilityUsers
                    ],
                    "user" => [
                        "id" => $story->user->id,
                        "fullName" => $story->user->fullName,
                        "slug" => $story->user->slug,
                        "avatar" => $story->user->getAvatar('48x48', $story->user->id)
                    ],
                    "images" => $images,
                    "links" => $links,
                    "books" => $books,
                    "likes" => $likes,
                    "comments" => $comments,
                    "counts" => [
                        "comments" => $commentCounts
                    ]
                ];
            }
        }

        return $data;
    }


    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            if (($this->getVisibility() == 1) && ($this->in_storyline == 1))
                $this->user->updateCounters(['stories_count' => 1]);
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['story_id' => 'id']);
    }


    /**
     * Check if current story is visible to this user
     *
     * @return bool
     */
    public function isVisibleForUser()
    {
        $userId = Yii::$app->user->id;

        if ($userId != $this->user_id) {
            $visibility = StoryPermissionSettings::findOne(['story_id' => $this->id]);

            if ($visibility->permission_state == StoryPermissionSettings::PRIVACY_TYPE_PRIVATE) {
                return false;
            }

            if ($visibility->permission_state == StoryPermissionSettings::PRIVACY_TYPE_PUBLIC) {
                return true;
            }

            if ($visibility->permission_state == StoryPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                if ($visibility->custom_permission_id != null) {
                    $users = ArrayHelper::getColumn(StoryCustomPermissions::find()->where(['custom_id' => $visibility
                        ->custom_permission_id])->all(), 'user_id');
                    if (is_array($users) && in_array($userId, $users)) {
                        return true;
                    }

                }
            }
        } else {
            return true;
        }


        return false;
    }


    public function getVisibility()
    {
        $setting = StoryPermissionSettings::findOne(['story_id' => $this->id]);

        return $setting->permission_state;
    }

    public function getVisibilityName()
    {
        $setting = StoryPermissionSettings::findOne(['story_id' => $this->id]);

        return $setting->getPermissionName();
    }

    public static function getVisibilityValue($value)
    {
        switch ($value) {
            case 'public':
                return StoryPermissionSettings::PRIVACY_TYPE_PUBLIC;
            case 'private':
                return StoryPermissionSettings::PRIVACY_TYPE_PRIVATE;
            case 'custom':
                return StoryPermissionSettings::PRIVACY_TYPE_CUSTOM;
            default:
                return null;
        }

    }

    public function getCustomVisibilityUsers()
    {
        $setting = StoryPermissionSettings::findOne(['story_id' => $this->id]);
        $users = ArrayHelper::getColumn(StoryCustomPermissions::find()->where(['custom_id' => $setting
            ->custom_permission_id])->all(), 'user_id');
        if (!empty($users)) {
            return $users;
        }
        return [];
    }


    public function getBooksIDs()
    {
        $ids = array();
        foreach ($this->books as $book)
            $ids[] = $book->book_id;

        return $ids;
    }

    /**
     * Check if current user can delete this story
     *
     * @return bool
     */
    public function canUserDelete()
    {
        $userId = Yii::$app->user->id;
        $mapPermissions = BookPermissionSettings::mapSettings();

        foreach ($this->books as $storyBook) {
            if ($storyBook->book->author_id == $userId) {
                return true;
            }

            $key = array_search('can_delete_stories', $mapPermissions);
            $visibility = BookPermissionSettings::findOne(['book_id' => $storyBook->book_id, 'permission_id' => $key]);


            if ($this->user_id == $userId && $visibility->permission_state != BookPermissionSettings::PRIVACY_TYPE_PRIVATE) {
                return true;
            }

            if ($visibility->permission_state == BookPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                $customModel = BookCustomPermissions::findAll(['custom_id' => $visibility->custom_permission_id]);
                $users = ArrayHelper::getColumn($customModel, 'user_id');
                if (is_array($users) && in_array($userId, $users))
                    return true;
            }
        }

        return false;
    }

    public function getSearchResult($q)
    {
        // $this->setPagination($itemsPerPage, $offset);

        $models = self::find()->alias('s')
            ->where('s.created_at > unix_timestamp(NOW() - INTERVAL 1 HOUR)')
            ->andWhere("`s`.`description` LIKE '$q%'")
            ->limit(100)
            //    ->offset($this->getOffset())
            ->all();

        return self::format($models);

    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function isMovedToBin()
    {
        /** @var StoryBook $model */
        $models = StoryBook::findAll(['story_id' => $this->id]);
        foreach ($models as $model) {
            $model->is_moved_to_bin = 1;
            $model->update();
            // job for making remove stories from followers channels
            Yii::$app->queue->push(new BlockedStoryInChannelsAfterLogJob([
                'userId' => Yii::$app->getUser()->getId(),
                'bookId' => $model->book_id,
                'storyId' => $model->story_id
            ]));
        }
        return true;
    }


}
