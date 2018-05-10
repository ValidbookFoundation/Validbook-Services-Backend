<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\search;

use app\modules\v1\helpers\StoryHelper;
use app\modules\v1\models\Comment;
use app\modules\v1\models\Like;
use app\modules\v1\models\story\StoryBook;
use app\modules\v1\models\story\StoryCustomPermissions;
use app\modules\v1\models\story\StoryFiles;
use app\modules\v1\models\story\StoryLinks;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\sphinx\ActiveRecord;
use yii\sphinx\MatchExpression;

/**
 * Class SearchStory
 * @package app\modules\v1\models\search
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $created_at
 * @property integer $started_on
 * @property integer $completed_on
 * @property integer $visibility_type
 * @property integer $in_storyline
 * @property integer $in_channels
 * @property integer $in_book
 */
class SearchStory extends ActiveRecord implements Search
{
    use PaginationTrait;

    public static function indexName()
    {
        return 'story';
    }

    private function matchResult($q)
    {
        $q = \Yii::$app->sphinx->escapeMatchValue($q);
        $user = Yii::$app->getUser()->identity;

        $friends = SearchFollow::find()
            ->select('he_id')
            ->where(['me_id' => $user->getId(), 'relation' => 'friends'])
            ->all();
        $follows = SearchFollow::find()
            ->select('he_id')
            ->where(['me_id' => $user->getId(), 'relation' => 'following'])
            ->all();
        $friendsAndFoll = array_merge($friends, $follows);

        $friendIds = ArrayHelper::getColumn($friendsAndFoll, 'he_id');

        $modelsF = self::find()
            ->match((new MatchExpression())
                ->match(['description' => $q])
            )
            ->where(['user_id' => $friendIds])
            ->orderBy('user_id, created_at DESC, id')
            ->all();


        $storyIds = ArrayHelper::getColumn($modelsF, 'id');

        $modelsO = self::find()
            ->match((new MatchExpression())
                ->match(['description' => $q])
            )
            ->where(['NOT IN', 'id', $storyIds])
            ->andWhere(['!=', 'user_id', $user->getId()])
            ->orderBy('user_id, created_at DESC, id')
            ->all();

        $models = array_merge($modelsF, $modelsO);

        if (!empty($this->getItemsPerPage())) {
            $this->setPagination($this->getItemsPerPage(), $this->getPage());
            $models = array_slice($models, $this->getOffset(), $this->getLimit());
        }

        return $models;
    }

    public function getSearchResult($q)
    {
        $data = [];

        $stories = $this->matchResult($q);


        if (!empty($stories)) {
            /** @var SearchStory $story */
            foreach ($stories as $story) {
                $created = Yii::$app->formatter->asDate($story->created_at);
                $exactCreated = Yii::$app->formatter->asDate($story->created_at, "dd MMM yyyy H:mm:ss");
                $startedOn = $story->started_on ? Yii::$app->formatter->asDate($story->started_on) : $created;
                $completedOn = $story->completed_on ? Yii::$app->formatter->asDate($story->completed_on) : null;

                $description = StoryHelper::replacePlaceholdersToTags($story->description);
                $visibilityType = $story->getVisibility();
                $customVisibilityUsers = $story->getCustomVisibilityUsers();


                $images = StoryFiles::getStoryImages($story->id);
                $links = StoryLinks::getStoryLinks($story->id);
                $books = StoryBook::getStoryBooks($story->id);
                $likes = Like::getStoryLikes($story);
                $commentModel = new Comment();
                $comments = $commentModel->getCommentsForStory($story->id, 1, 'storyline');
                /** @var User $user */
                $user = User::find()->where(['id' => $story->user_id])->one();
                /*--------------------*/

                $data[$story->id] = [
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
                        "status" => $visibilityType,
                        "customUsers" => $customVisibilityUsers
                    ],
                    "user" => [
                        "id" => $user->id,
                        "fullName" => $user->getFullName(),
                        "slug" => $user->slug,
                        "avatar" => $user->getAvatar('32x32', $user->id)
                    ],
                    "images" => $images,
                    "links" => $links,
                    "books" => $books,
                    "likes" => $likes,
                    "comments" => $comments
                ];
            }
        }
        return $data;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    private function getVisibility()
    {
        $setting = StoryPermissionSettings::findOne(['story_id' => $this->id]);
        return $setting->permission_state;
    }

    private function getCustomVisibilityUsers()
    {
        $setting = StoryPermissionSettings::findOne(['story_id' => $this->id]);
        $users = ArrayHelper::getColumn(StoryCustomPermissions::find()->where(['custom_id' => $setting
            ->custom_permission_id])->all(), 'user_id');
        if (!empty($users)) {
            return $users;
        }
        return [];
    }

}