<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\models\book\Book;
use app\modules\v1\models\following\Follow;
use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\redis\ChannelStory;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "channel".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string slug
 * @property string $description
 * @property integer $is_default
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Channel extends ActiveRecord
{
    const DEFAULT_CHANNEL_NAME = "Mashup";
    const DEFAULT_CHANNEL_SLUG = "mashup";

    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'channel';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['user_id', 'is_default'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'is_default' => 'Is Default',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
            'slug' => [
                'class' => Slug::className(),
                'slugAttribute' => 'slug',
                'attribute' => 'name',
                // optional params
                'ensureUnique' => false,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => false,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDefault()
    {
        return self::find()->where([
            'is_default' => 1,
            'user_id' => $this->user->id
        ])->one();
    }

    public function getDefaultChannelName()
    {
        return self::DEFAULT_CHANNEL_NAME;
    }

    public function getChannelStories()
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());


        $stories = ChannelStory::find()
            ->where(['channel_id' => $this->id, 'is_blocked' => false])
            ->all();


        $storyIds = ArrayHelper::getColumn($stories, 'story_id');

        $storiesModels = Story::find()
            ->where(['id' => $storyIds])
            ->innerJoin('user u', 'story.user_id = u.id')
            ->where(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return Story::format($storiesModels);
    }

    public function setPeopleForChannel($modelUser, $people)
    {
        $result = [];

        $people = array_unique($people);

        $followersInChannels = Follow::findAll([
            'channel_id' => $this->id,
            'user_id' => $modelUser->getId(),
            'followee_id' => $people,
            'is_follow' => 1, 'is_block' => 0
        ]);

        if (!empty($followersInChannels)) {
            $followersInChannels = ArrayHelper::getColumn($followersInChannels, 'followee_id');
            foreach ($followersInChannels as $follower) {
                $key = array_search($follower, $people);
                unset($people[$key]);
            }
        }
        foreach ($people as $id) {
            $follow = new Follow();
            $follow->channel_id = $this->id;
            $follow->user_id = $modelUser->getId();
            $follow->followee_id = $id;
            $follow->is_follow = 1;
            $follow->is_block = 0;
            $follow->save();
        }

        $follow = new Follow();

        $result[] = $follow->userFollowingForChannel($modelUser, $this->id);

        return $result;
    }

    public function setBooksForChannel($modelUser, $books)
    {
        $followBooksInChannels = FollowBook::findAll([
            'channel_id' => $this->id,
            'user_id' => $modelUser->getId(),
            'book_id' => $books,
            'is_follow' => 1, 'is_block' => 0
        ]);
        if (!empty($followBooksInChannels)) {
            $followBooksInChannels = ArrayHelper::getColumn($followBooksInChannels, 'book_id');
            foreach ($followBooksInChannels as $follBook) {
                $key = array_search($follBook, $books);
                unset($books[$key]);
            }
        }

        foreach ($books as $bookId) {
            $follow = new FollowBook();
            $follow->channel_id = $this->id;
            $follow->user_id = $modelUser->getId();
            $follow->book_id = $bookId;
            $follow->is_follow = 1;
            $follow->is_block = 0;
            $follow->save();
        }
    }

    private function getAllowedStories($userId)
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $followersIds = $this->getFollowersIds($userId);
        $booksIds = $this->getFollowingBooksIds($userId);

        $myStoriesInChannelIds = [];

        if ($this->slug == self::DEFAULT_CHANNEL_SLUG) {
            $myStoriesInChannelIds = $this->getMyStoriesIds();
        }

        $storiesFollowersIds = $this->getStoriesFollowersIds($followersIds);
        $storiesBooksIds = $this->getStoriesBooksIds($booksIds);

        $queryStoriesId = array_unique(array_merge($storiesFollowersIds, $storiesBooksIds, $myStoriesInChannelIds));

        $models = Story::find()
            ->where(['id' => $queryStoriesId])
            ->orderBy('created_at DESC')
            ->limit($this->getLimit())
            ->all();

        return $models;
    }


    private function getStoriesFollowersIds($followersIds)
    {
        $myStoriesFollowersIds = [];
        $userId = Yii::$app->getUser()->getId();

        $minDate = (new Query())
            ->select('min(story_created_at) as min_date')
            ->from('channel_story')
            ->where(['channel_id' => $this->id])
            ->one();

        if ($this->slug !== self::DEFAULT_CHANNEL_SLUG and in_array($userId, $followersIds)) {
            $key = array_search($userId, $followersIds);
            unset($followersIds[$key]);
            $myStoriesFollowers = Story::find()->alias('s')
                ->innerJoin('story_permission_settings ss', 's.id = ss.story_id')
                ->innerJoin('user u', 's.user = u.id')
                ->where(['s.user_id' => $userId, 's.in_channels' => 1])
                ->andWhere(['<', 's.created_at', (int)$minDate['min_date']])
                ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->orderBy('s.created_at DESC')
                ->all();

            $myStoriesFollowersIds = ArrayHelper::getColumn($myStoriesFollowers, 'id');
        }

        $storiesFollowers = Story::find()->alias('s')
            ->innerJoin('story_permission_settings ss', 's.id = ss.story_id')
            ->innerJoin('user u', 's.user = u.id')
            ->leftJoin('story_custom_permissions scp', 'ss.custom_permission_id = scp.custom_id')
            ->where([
                's.user_id' => $followersIds,
                's.in_channels' => 1,
                's.in_book' => 1,
                'ss.permission_state' => [StoryPermissionSettings::PRIVACY_TYPE_PUBLIC, StoryPermissionSettings::PRIVACY_TYPE_CUSTOM]])
            ->andWhere(['<', 's.created_at', (int)$minDate['min_date']])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orWhere(['scp.user_id' => $userId])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->orderBy('s.created_at DESC')
            ->all();

        $storiesFollowersIds = ArrayHelper::getColumn($storiesFollowers, 'id');

        return array_merge($storiesFollowersIds, $myStoriesFollowersIds);
    }

    private function getStoriesBooksIds($booksIds)
    {
        $myStoriesBooksIds = $myBooksIds = [];

        $minDate = (new Query())
            ->select('min(story_created_at) as min_date')
            ->from('channel_story')
            ->where(['channel_id' => $this->id])
            ->one();

        $userId = Yii::$app->getUser()->getId();

        if ($this->slug !== self::DEFAULT_CHANNEL_SLUG) {
            $myBooks = Book::findAll(['author_id' => $userId, 'id' => $booksIds]);
            foreach ($myBooks as $book) {
                if (in_array($book->id, $booksIds)) {
                    $key = array_search($book->id, $booksIds);
                    unset($booksIds[$key]);
                }
            }
            $myBooksIds = ArrayHelper::getColumn($myBooks, 'id');

        }

        if ($this->slug !== self::DEFAULT_CHANNEL_SLUG) {
            $myStoriesBooks = Story::find()->alias('s')
                ->innerJoin('story_book sb', 's.id = sb.story_id')
                ->innerJoin('story_permission_settings ss', 's.id = ss.story_id')
                ->where(['sb.book_id' => $myBooksIds, 's.in_channels' => 1, 's.in_book' => 1])
                ->andWhere(['<', 's.created_at', (int)$minDate['min_date']])
                ->orderBy('s.created_at DESC')
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->all();
            $myStoriesBooksIds = ArrayHelper::getColumn($myStoriesBooks, 'id');
        }

        $storiesBooks = Story::find()->alias('s')
            ->innerJoin('user u', 's.user = u.id')
            ->innerJoin('story_book sb', 's.id = sb.story_id')
            ->innerJoin('story_permission_settings ss', 's.id = ss.story_id')
            ->leftJoin('story_custom_permissions scp', 'ss.custom_permission_id = scp.custom_id')
            ->where(['sb.book_id' => $booksIds, 's.in_channels' => 1, 's.in_book' => 1,
                'ss.permission_state' => [StoryPermissionSettings::PRIVACY_TYPE_PUBLIC, StoryPermissionSettings::PRIVACY_TYPE_CUSTOM]])
            ->andWhere(['<', 's.created_at', (int)$minDate['min_date']])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orWhere(['scp.user_id' => $userId])
            ->orderBy('s.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $storiesBooksIds = ArrayHelper::getColumn($storiesBooks, 'id');

        return array_merge($storiesBooksIds, $myStoriesBooksIds);
    }

    private function getMyStoriesIds()
    {
        $userId = Yii::$app->getUser()->getId();

        $minDate = (new Query())
            ->select('min(story_created_at) as min_date')
            ->from('channel_story')
            ->where(['channel_id' => $this->id])
            ->one();

        $myStoriesIds = Story::find()->alias('s')
            ->where(['s.user_id' => $userId, 's.in_channels' => 1])
            ->andWhere(['<', 's.created_at', (int)$minDate['min_date']])
            ->orderBy('s.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();


        return ArrayHelper::getColumn($myStoriesIds, 'id');

    }

    private function getFollowersIds($userId)
    {
        $channelId = $this->id;

        $followers = (new Query())
            ->select('followee_id')
            ->from('follow')
            ->innerJoin('user u', 'followee_id = u.id')
            ->where([
                'user_id' => $userId,
                'channel_id' => $channelId,
                'is_follow' => 1,
                'is_block' => 0])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->all();

        $followersIds = ArrayHelper::getColumn($followers, 'followee_id');

        return $followersIds;
    }

    private function getFollowingBooksIds($userId)
    {
        $follBooks = (new Query())
            ->select('book_id')
            ->from('follow_book')
            ->where([
                'user_id' => $userId,
                'is_follow' => 1,
                'is_block' => 0,
                'channel_id' => $this->id])
            ->all();

        $followBooksIds = ArrayHelper::getColumn($follBooks, 'book_id');

        return $followBooksIds;
    }

    public function addedContent($modelUser, $content)
    {
        $people = $counts = [];
        if (!empty($content['books'])) {
            $this->setBooksForChannel($modelUser, $content['books']);

            $counts['books'] = (int)FollowBook::find()->where([
                'channel_id' => $this->id,
                'user_id' => $modelUser->getId(),
                'is_follow' => 1,
                'is_block' => 0])
                ->count();
        }

        if (!empty($content['people'])) {
            $people = $this->setPeopleForChannel($modelUser, $content['people']);

            if ($this->slug == 'mashup') {
                $counts['people'] = (int)Follow::find()->where([
                    'channel_id' => $this->id,
                    'user_id' => $modelUser->getId(),
                    'is_follow' => 1,
                    'is_block' => 0
                ])
                    ->andWhere(['!=', 'followee_id', $modelUser->getId()])
                    ->count();
            } else {
                $counts['people'] = (int)Follow::find()->where([
                    'channel_id' => $this->id,
                    'user_id' => $modelUser->getId(),
                    'is_follow' => 1,
                    'is_block' => 0
                ])->count();
            }

        }

        $responseData = [
            'people' => $people,
            'counts' => $counts
        ];

        return $responseData;
    }

    public function removedContent($modelUser, $content)
    {
        if (!empty($content['books'])) {
            $this->deleteBooksInChannel($modelUser, $content['books']);
        }
        if (!empty($content['people'])) {
            $this->deletePeopleInChannel($modelUser, $content['people']);
        }
    }

    private function deleteBooksInChannel($modelUser, $books)
    {
        $followBooksInChannels = FollowBook::findAll([
            'channel_id' => $this->id,
            'user_id' => $modelUser->getId(),
            'book_id' => $books,
            'is_follow' => 1, 'is_block' => 0
        ]);
        if (!empty($followBooksInChannels)) {
            foreach ($followBooksInChannels as $follBook) {
                $follBook->delete();
            }
            return true;
        }
        return false;
    }

    private function deletePeopleInChannel($modelUser, $people)
    {
        $followersInChannels = Follow::findAll([
            'channel_id' => $this->id,
            'user_id' => $modelUser->getId(),
            'followee_id' => $people,
            'is_follow' => 1, 'is_block' => 0
        ]);

        if (!empty($followersInChannels)) {
            foreach ($followersInChannels as $follower) {
                $follower->delete();
            }
            return true;
        }
        return false;
    }
}
