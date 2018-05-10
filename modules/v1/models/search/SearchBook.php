<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\search;


use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\story\StoryBook;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\sphinx\ActiveRecord;

/**
 * Class SearchBook
 * @package app\modules\v1\models\search
 *
 * @property $id
 * @property $book_id
 * @property $author_id
 * @property $name
 * @property $description
 * @property $slug
 * @property $cover
 * @property $created_at
 */
class SearchBook extends ActiveRecord implements Search
{
    const URLDELIMITER = "-";

    public static $offset = 1;
    public static $itemsPerPage = 12;

    public $url;

    use PaginationTrait;

    public static function indexName()
    {
        return 'book';
    }

    public function getName()
    {
        return utf8_decode($this->name);
    }

    public function getDescription()
    {
        return utf8_decode($this->description);
    }

    public function getSlug()
    {
        return utf8_decode($this->slug);
    }

    private function matchResult($q)
    {
        $q = Yii::$app->sphinx->escapeMatchValue($q);
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
            ->match($q)
            ->where(['author_id' => $friendIds])
            ->orderBy('created_at DESC')
            ->all();

        $booksFIds = ArrayHelper::getColumn($modelsF, 'book_id');

        $modelsO = self::find()
            ->match($q)
            ->where(['NOT IN', 'book_id', $booksFIds])
            ->andWhere(['!=', 'author_id', $user->getId()])
            ->orderBy('created_at DESC')
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
        $result = [];

        $data = $this->matchResult($q);

        $user = Yii::$app->getUser()->identity;

        if ($user !== null) {
            if (!empty($data)) {
                /** @var SearchBook $book */
                foreach ($data as $book) {
                    $user = User::find()->where(['id' => $book->author_id])->one();
                    $result[$book->id] = [
                        'id' => $book->id,
                        "name" => $book->getName(),
                        "slug" => $book->getUrl(),
                        "description" => $book->description,
                        "cover" => $book->cover,
                        'created' => Yii::$app->formatter->asDate($book->created_at),
                        'owner' => [
                            "id" => $user->id,
                            "fullName" => $user->getFullName(),
                            "slug" => $user->slug,
                            "avatar" => $user->getAvatar('32x32', $user->id)
                        ],
                        "counters" => [
                            "stories" => (int)StoryBook::find()
                                ->joinWith(['story'])
                                ->innerJoin('story_permission_settings sps', 'story_book.story_id = sps.story_id')
                                ->where([
                                    'book_id' => $book->id,
                                    'is_moved_to_bin' => 0,
                                    "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC
                                ])->count(),
                            "followers" => (int)FollowBook::find()->where(['book_id' => $book->id, 'is_follow' => 1])->count()
                        ],
                    ];
                }
            }
        } else {
            return [];
        }

        return $result;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getUrl()
    {
        $this->url = $this->id . self::URLDELIMITER . $this->slug;

        return $this->url;
    }


}