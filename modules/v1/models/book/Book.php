<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\book;

use app\modules\v1\models\Cover;
use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\search\Search;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\story\StoryBook;
use app\modules\v1\models\story\StoryFiles;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\models\TreeQuery;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use creocoder\nestedsets\NestedSetsBehavior;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property string $slug
 * @property integer $author_id
 * @property integer $is_root
 * @property integer $is_default
 * @property string $description
 * @property string $cover
 * @property string $created_at
 * @property string $updated_at
 * @property integer $auto_import
 * @property integer $auto_export
 * @property integer $is_moved_to_bin
 *
 * @property User $author
 * @property FollowBook[] $followBooks
 * @property \app\modules\v1\models\story\StoryBook[] $storyBooks
 */
class Book extends ActiveRecord implements Search
{
    private $_url;

    const DEFAULT_BOOK_NAME = "Wallbook";
    const URLDELIMITER = "-";

    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['lft', 'rgt', 'depth', 'author_id', 'is_root', 'is_default', 'auto_import', 'auto_export', 'is_moved_to_bin'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'cover'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
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
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['book.id', 'book.slug', 'book.updated_at']);
                    $model->andWhere([
                        'book.is_root' => 0
                    ]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to($model->getFullurl(), true),
                        'lastmod' => $model->updated_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    //get full url for sitemap
    public function getFullurl()
    {
        if (!isset($this->author->slug)) {
            return Yii::$app->params['siteUrl'] . '/' . $this->author . "/books/" . $this->url;
        }
    }


    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new TreeQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowBooks()
    {
        return $this->hasMany(FollowBook::className(), ['book_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStories()
    {
        return $this->hasMany(StoryBook::className(), ['book_id' => 'id']);
    }

    public function getDefaultBookName()
    {
        return self::DEFAULT_BOOK_NAME;
    }

    public function createDefault($userId)
    {
        $rootBookId = $this->createRoot($userId);

        $parentModel = Book::findOne([
            'id' => $rootBookId
        ]);
        $model = new Book();
        $model->name = $this->defaultBookName;
        $model->author_id = $userId;
        $model->is_default = 1;
        if ($model->prependTo($parentModel)) {
            BookPermissionSettings::setValues($model->id);
        }
    }

    public function createRoot($userId, $name = 'root')
    {
        if (self::find()
            ->where([
                'author_id' => $userId,
                'depth' => 0,
                'lft' => 0
            ])
            ->exists()) {

            return false;
        }

        $model = new Book([
            'name' => $name,
            'author_id' => $userId,
            'is_root' => 1
        ]);

        $model->makeRoot();

        return $model->id;
    }

    public function getBookStories()
    {
        $userId = Yii::$app->user->getId();

        if ($userId == $this->author_id) {
            $result = $this->getStoriesForOwner();
        } else {
            $result = $this->getStoriesForGuest();
        }


        return Story::format($result);
    }

    public function getStoriesForOwner()
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $models = Story::find()->alias('s')
            ->leftJoin('story_book sb', 'sb.story_id = s.id')
            ->innerJoin('story_permission_settings sp', 'sp.story_id = s.id')
            ->where(['sb.book_id' => $this->id, 'sb.is_moved_to_bin' => 0,
                'sp.permission_state' => [StoryPermissionSettings::PRIVACY_TYPE_PUBLIC, StoryPermissionSettings::PRIVACY_TYPE_CUSTOM]])
            ->orderBy('sb.pin_order DESC, sb.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return $models;
    }

    public function getStoriesForGuest()
    {

        $storyIds = $this->getAllowedStoriesIds();

        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $models = Story::find()->alias('s')
            ->leftJoin('story_book sb', 's.id=sb.story_id')
            ->innerJoin('story_permission_settings sp', 'sp.story_id = s.id')
            ->where(['s.id' => $storyIds,
                'sb.is_moved_to_bin' => 0,
                'sp.permission_state' => [StoryPermissionSettings::PRIVACY_TYPE_PUBLIC, StoryPermissionSettings::PRIVACY_TYPE_CUSTOM]])
            ->orderBy('sb.pin_order DESC, sb.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return $models;
    }

    public function getAllowedStoriesIds()
    {
        $bookId = $this->id;
        $userId = Yii::$app->user->id;

        $stories = (new Query())
            ->from('story s')
            ->innerJoin('story_book sb', 's.id = sb.story_id')
            ->innerJoin('story_permission_settings sp', 'sp.story_id = s.id')
            ->where(['sb.book_id' => $bookId,
                's.in_book' => 1,
                'sb.is_moved_to_bin' => 0,
                'sp.permission_state' => [StoryPermissionSettings::PRIVACY_TYPE_PUBLIC, StoryPermissionSettings::PRIVACY_TYPE_CUSTOM]])
            ->orderBy('s.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();


        foreach ($stories as $key => $story) {
            if ($story['permission_state'] == StoryPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                $customStories = BookCustomPermissions::findAll(['custom_id' => $story['custom_permission_id']]);
                $users = ArrayHelper::getColumn($customStories, 'user_id');
                if (!in_array($userId, $users)) {
                    unset($stories[$key]);
                }
            }
        }

        $ids = ArrayHelper::getColumn($stories, 'story_id');

        return $ids;
    }

    public function getUrl()
    {
        $this->_url = $this->id . self::URLDELIMITER . $this->slug;

        return $this->_url;
    }


    public function getIcon()
    {
        $bookSettings = BookPermissionSettings::find()->where(['book_id' => $this->id])->all();

        $bookSettings = ArrayHelper::getColumn(ArrayHelper::index($bookSettings, 'permission_id'), 'permission_state');


        if ($bookSettings[1] == 1 && $bookSettings[2] == 1) {
            return "public";
        }
        if (($bookSettings[1] == 0) or ($bookSettings[2] == 0)) {
            return "private";

        }
        if (($bookSettings[1] == 2) or ($bookSettings[2] == 2)) {
            return "custom";
        }

    }

    public static function getRemovedBooks($userId, $models)
    {
        $books = [];
        /** @var Book $book */
        foreach ($models as $book) {
            $books[] = [
                'name' => $book['name'],
                'key' => $book['id'] . "-" . $book['slug'],
                'icon' => 'bin',
                'no_drag' => true,
                'counts' => $book->getBookCounts()
            ];
        }

        return $books;
    }

    public function isInBin()
    {
        return boolval($this->is_moved_to_bin);
    }


    /**
     * Check if current user can see existence of this book
     *
     * @return bool
     */
    public function isExistenceVisibile()
    {
        return $this->checkVisibility('can_see_exists');
    }

    /**
     * Check if current user can see content of this book
     *
     * @return bool
     */
    public function isContentVisible()
    {
        return $this->checkVisibility('can_see_content');
    }

    /**
     * @return bool
     */
    private function checkVisibility($field)
    {
        $userId = isset(Yii::$app->user) ? Yii::$app->user->id : null;
        $bookSettings = BookPermissionSettings::find()->where(['book_id' => $this->id])->all();
        $mapSettings = BookPermissionSettings::mapSettings();

        $key = array_search($field, $mapSettings, true);

        /** @var BookPermissionSettings $setting */
        foreach ($bookSettings as $setting) {
            if ($key == $setting->permission_id) {
                if ($setting->permission_state == BookPermissionSettings::PRIVACY_TYPE_PUBLIC) {
                    return true;
                } elseif ($setting->permission_state == BookPermissionSettings::PRIVACY_TYPE_PRIVATE) {
                    return false;
                } elseif ($setting->permission_state == BookPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                    $customs = (new Query())
                        ->from('book_custom_permissions')
                        ->where(['custom_id' => $setting->custom_permission_id])
                        ->all();
                    $users = ArrayHelper::getColumn($customs, 'user_id');
                    if (in_array($userId, $users)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canUserUpdate($user)
    {
        if ($user->getId() == $this->author_id) {
            return true;
        }

        /** @var BookPermissionSettings $perSetting */
        $perSetting = BookPermissionSettings::find()->where(['book_id' => $this->id, 'permission_id' => 5])->one();

        if ($perSetting->permission_state == BookPermissionSettings::PRIVACY_TYPE_CUSTOM) {
            $users = ArrayHelper::getColumn(BookCustomPermissions::find()->where(['custom_id' => $perSetting->custom_permission_id])->all(), 'user_id');
            if (is_array($users) && in_array($user->getId(), $users)) {
                return true;
            }

        }

        return false;
    }

    public function getSearchResult($q)
    {
        $result = [];

        $models = self::find()->alias('b')
            ->innerJoin('user u', 'b.author_id = u.id')
            ->where('b.created_at > unix_timestamp(NOW() - INTERVAL 1 HOUR)')
            ->andWhere("`b`.`name` LIKE '$q%' OR
       `b`.`description` LIKE '$q%' OR `b`.`slug` LIKE '$q%'")
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->limit(100)
            ->all();

        /** @var Book $book */
        foreach ($models as $book) {
            $user = User::find()->where(['id' => $book->author_id])->one();
            $result[$book->id] = [
                'id' => $book->id,
                "name" => $book->name,
                "slug" => $book->getUrl(),
                "description" => $book->description,
                "cover" => $book->getCover(),
                'created' => Yii::$app->formatter->asDate($book->created_at),
                'owner' => [
                    "id" => $user->id,
                    "fullName" => $user->getFullName(),
                    "slug" => $user->slug,
                    "avatar" => $user->getAvatar('32x32', $user->id)
                ],
                "counts" => $this->getBookCounts()
            ];
        }

        if (!empty($this->getItemsPerPage())) {
            $this->setPagination($this->getItemsPerPage(), $this->getPage());
            $result = array_slice($result, $this->getOffset(), $this->getLimit());
        }

        return $result;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public static function getOtherSettings()
    {
        $settings = [
            'auto_export' => Yii::$app->request->post('auto_export', 1),
            'auto_import' => Yii::$app->request->post('auto_import', 1)
        ];
        return $settings;
    }

    public function setOtherSettings(array $bookOtherSettings)
    {
        foreach ($bookOtherSettings as $key => $setting) {
            if ($key = 'auto_import') {
                $this->auto_import = $setting;
            }
            if ($key = 'auto_export') {
                $this->auto_export = $setting;
            }

        }
    }

    public function getSettings()
    {
        $data = [];
        $bookSettings = BookPermissionSettings::find()->where(['book_id' => $this->id])->all();
        $bookSettings = ArrayHelper::index($bookSettings, 'permission_id');
        $mapSettings = BookPermissionSettings::mapSettings();
        foreach ($mapSettings as $key => $setting) {
            $data[$setting] = $bookSettings[$key]->permission_state;
        }
        $data['users_array'] = [
            'users_can_see_exists' => $bookSettings[1]->permission_state == 2 ? BookCustomPermissions::getUsers($bookSettings[1]->custom_permission_id) : [],
            'users_can_see_content' => $bookSettings[2]->permission_state == 2 ? BookCustomPermissions::getUsers($bookSettings[2]->custom_permission_id) : [],
            'users_can_add_stories' => $bookSettings[3]->permission_state == 2 ? BookCustomPermissions::getUsers($bookSettings[3]->custom_permission_id) : [],
            'users_can_delete_stories' => $bookSettings[4]->permission_state == 2 ? BookCustomPermissions::getUsers($bookSettings[4]->custom_permission_id) : [],
            'users_can_manage_settings' => $bookSettings[5]->permission_state == 2 ? BookCustomPermissions::getUsers($bookSettings[5]->custom_permission_id) : []
        ];

        return $data;
    }

    public function childs($node, $modelUser)
    {
        $identityId = Yii::$app->user->getId();
        if (empty($node))
            return [];

        $books = [];
        $children = $node->children(1)->all();

        /** @var Book $child */
        foreach ($children as $key => $child) {
            if ($modelUser->getId() !== $identityId) {
                $childPermissions = BookPermissionSettings::findAll(['book_id' => $child->id]);
                foreach ($childPermissions as $permission) {
                    if ($permission->permission_id == 1 and $permission->permission_state == 0) {
                        unset($children[$key]);
                    }
                    if ($permission->permission_id == 1 and $permission->permission_state == 2) {
                        $customUsersIds = ArrayHelper::getColumn(BookCustomPermissions::findAll(['custom_id' => $permission->custom_permission_id]), 'user_id');
                        if (!in_array($identityId, $customUsersIds)) {
                            unset($children[$key]);
                        }
                    }
                }
            }
            if (!$child->isInBin()) {

                $bookItem = [
                    'name' => $child->name,
                    'key' => $child->getUrl(),
                    'icon' => $child->getIcon(),
                    'cover' => $child->getCover(),
                    'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/books/' . $child->getUrl()], true),
                    'auto_export' => $child->auto_export,
                    'auto_import' => $child->auto_import
                ];

                if ($child->is_default == 1) {
                    $wallbook = array_merge($bookItem, [
                        'no_drag' => true,
                    ]);

                    //add wallbook to the beginning of the array
                    array_unshift($books, $wallbook);
                } else {
                    $children = $this->childs($child, $modelUser);

                    $books[] = array_merge($bookItem, [
                        'no_drag' => false,
                        'children' => $children,
                    ]);
                }
            }
        }

        return $books;
    }


    public function oneLevelChilds($modelUser, $page)
    {
        $identityId = Yii::$app->user->getId();

        $books = [];

        $this->setPagination(16, $page);

        $children = $this->children(1)->limit($this->getLimit())->offset($this->getOffset())->all();


        /** @var Book $child */
        foreach ($children as $key => $child) {
            if ($modelUser->getId() !== $identityId) {
                $childPermissions = BookPermissionSettings::findAll(['book_id' => $child->id]);
                foreach ($childPermissions as $permission) {
                    if ($permission->permission_id == 1 and $permission->permission_state == 0) {
                        unset($children[$key]);
                    }
                    if ($permission->permission_id == 1 and $permission->permission_state == 2) {
                        $customUsersIds = ArrayHelper::getColumn(BookCustomPermissions::findAll(['custom_id' => $permission->custom_permission_id]), 'user_id');
                        if (!in_array($identityId, $customUsersIds)) {
                            unset($children[$key]);
                        }
                    }
                }
            }
            if (!$child->isInBin()) {

                $counts = $child->getBookCounts();

                $bookItem = [
                    'name' => $child->name,
                    'key' => $child->getUrl(),
                    'icon' => $child->getIcon(),
                    'cover' => $child->getCover(),
                    'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/books/' . $child->getUrl()], true),
                    'auto_export' => $child->auto_export,
                    'auto_import' => $child->auto_import
                ];

                if ($child->is_default == 1) {
                    $wallbook = array_merge($bookItem, [
                        'no_drag' => true,
                    ]);

                    //add wallbook to the beginning of the array
                    array_unshift($books, $wallbook);
                } else {

                    $books[] = array_merge($bookItem, [
                        'no_drag' => false,
                        'children' => [],
                        'counts' => $counts
                    ]);
                }
            }
        }

        return $books;
    }

    public function addBinTree($userId)
    {
        $models = self::findAll(['author_id' => $userId, 'is_moved_to_bin' => 1]);

        $removedBooks = Book::getRemovedBooks($userId, $models);

        $removedBooksIds = ArrayHelper::getColumn($models, 'id');

        $bin = [
            'name' => "Bin",
            'key' => "bin",
            'icon' => "bin",
            'cover' => Yii::$app->params['defaultBookCoverColor'],
            'no_drag' => true,
            'children' => $removedBooks,
            'counts' => [
                "stories" => (int)StoryBook::find()
                    ->leftJoin('story', 'story_book.story_id = story.id')
                    ->leftJoin('story_permission_settings sps', 'story_book.story_id = sps.story_id')
                    ->where([
                        'story_book.book_id' => $removedBooksIds,
                        "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC
                    ])->count(),
                "sub_books" => (int)Book::find()->where(['is_moved_to_bin' => 1])->count(),
                "followers" => (int)FollowBook::find()->where(['book_id' => $removedBooksIds])->count(),
                "images" => (int)StoryFiles::find()->alias('sf')
                    ->innerJoin('story_book sb', 'sf.story_id = sb.story_id')
                    ->innerJoin('story_permission_settings sps', 'sf.story_id = sps.story_id')
                    ->where(['sb.book_id' => $removedBooksIds, "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC])
                    ->count()
            ]
        ];

        return $bin;
    }

    public function getBookCounts()
    {
        return [
            "stories" => (int)StoryBook::find()
                ->leftJoin('story', 'story_book.story_id = story.id')
                ->leftJoin('story_permission_settings sps', 'story_book.story_id = sps.story_id')
                ->where([
                    'story_book.book_id' => $this->id,
                    "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC
                ])->count(),
            "sub_books" => (int)$this->children(1)->count(),
            "followers" => (int)FollowBook::find()->where(['book_id' => $this->id])->count(),
            "images" => (int)StoryFiles::find()->alias('sf')
                ->innerJoin('story_book sb', 'sf.story_id = sb.story_id')
                ->innerJoin('story_permission_settings sps', 'sf.story_id = sps.story_id')
                ->where(['sb.book_id' => $this->id, "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC])
                ->count()
        ];
    }

    public function getCover()
    {
        $covers = Cover::findAll(['model_id' => $this->id, 'is_actual' => true, 'type' => Cover::BOOK_TYPE]);
        if (count($covers) == 2) {
            $result = [
                'picture_original' => $covers[0]->getUrl(),
                'picture_small' => $covers[1]->getUrl(),
                'color' => null
            ];
        } else {
            if (!empty($this->cover)) {
                $result = [
                    'picture_original' => null,
                    'picture_small' => null,
                    'color' => $this->cover
                ];
            } else {
                $result = [
                    'picture_original' => null,
                    'picture_small' => null,
                    'color' => Yii::$app->params['defaultBookCoverColor']
                ];
            }
        }

        return $result;
    }

}
