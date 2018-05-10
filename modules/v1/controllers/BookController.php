<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\book\BookPermissionSettings;
use app\modules\v1\models\book\KnockBook;
use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\story\StoryBook;
use app\modules\v1\models\story\StoryFiles;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\models\User;
use Yii;
use yii\db\Transaction;


class BookController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = array_merge($behaviors['authenticator']['except'], ['view']);

        return $behaviors;
    }

    public function actionTree()
    {
        $userSlug = Yii::$app->request->get('user_slug');

        if (empty($userSlug)) {
            $modelUser = Yii::$app->user->identity;
        } else {
            $modelUser = User::find()->where(['slug' => $userSlug, 'status' => User::STATUS_ACTIVE])->one();
        }

        if ($modelUser !== null) {

            $booksData = [];
            $roots = Book::find()
                ->where(['author_id' => $modelUser->getId()])
                ->roots()
                ->all();
            /** @var Book $root */
            foreach ($roots as $root) {
                $children = $root->childs($root, $modelUser);
                $children[] = $root->addBinTree($modelUser->getId());

                $booksData[] = [
                    'name' => $root->name,
                    'key' => 'root',
                    'show' => true,
                    'children' => $children,
                ];
            }

            return $this->success($booksData);
        } else

            return $this->failure("Tree doesn't exist");
    }

    public function actionIndex()
    {
        $userSlug = Yii::$app->request->get('user_slug');
        $bookSlug = Yii::$app->request->get('book_slug');
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page))
            return $this->failure("Invalid parameter 'page'", 400);

        if (empty($userSlug)) {
            $modelUser = Yii::$app->user->identity;
        } else {
            $modelUser = User::find()->where(['slug' => $userSlug, 'status' => User::STATUS_ACTIVE])->one();
        }

        if ($modelUser !== null) {
            $roots = Book::find()
                ->where(['author_id' => $modelUser->getId()])
                ->roots()->all();

            $booksData = [];
            if (!empty($bookSlug)) {
                if ($bookSlug == 'bin') {
                    $children[] = $roots[0]->addBinTree($modelUser->getId());
                    $booksData[] = [
                        'name' => 'Bin',
                        'key' => 'bin',
                        'show' => true,
                        'children' => $children,
                    ];
                } else {
                    $bookId = $this->parseBookId($bookSlug);
                    $book = Book::findOne(['author_id' => $modelUser->getId(), 'id' => $bookId]);
                    $children = $book->OneLevelChilds($modelUser, $page);
                    $booksData[] = [
                        'name' => $book->name,
                        'key' => $book->slug,
                        'show' => true,
                        'children' => $children,
                    ];
                }
            } else {

                /** @var Book $root */
                foreach ($roots as $root) {
                    $children = $root->OneLevelChilds($modelUser, $page);
                    if (count($children) < 16 && $page == 1) {
                        $children[] = $root->addBinTree($modelUser->id);
                    } elseif (count($children) == 0) {
                        continue;
                    } else {
                        $children[] = $root->addBinTree($modelUser->id);
                    }

                    $booksData[] = [
                        'name' => $root->name,
                        'key' => 'root',
                        'show' => true,
                        'children' => $children,
                    ];
                }
            }

            return $this->success($booksData);
        } else

            return $this->failure("Tree doesn't exist");
    }

    public function actionCreate()
    {
        $name = Yii::$app->request->post('name');
        $description = Yii::$app->request->post('description');
        $parentSlug = Yii::$app->request->post('parent_slug');
        $bookOtherSettings = Book::getOtherSettings();
        $userId = Yii::$app->user->getId();

        if ($parentSlug == 'root') {
            $parentModel = Book::find()->where([
                'is_root' => 1,
                'author_id' => $userId
            ])->one();
        } else {
            $bookId = $this->parseBookId($parentSlug);

            $parentModel = Book::find()->where([
                'id' => $bookId,
                'author_id' => $userId
            ])->one();
        }

        if ($parentModel == null)
            return $this->failure("Parent book does not exists");

        if ($parentModel->is_default == 1)
            return $this->failure("Wallbook can not have childs");

        if (!$this->hasOwnerAccessRights(Book::className(), 'author_id', $parentModel->id))
            return $this->failure("You are not allowed to perform this action");

        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {

            $model = new Book([
                'name' => $name,
                'author_id' => $userId,
                'description' => $description
            ]);

            $model->setOtherSettings($bookOtherSettings);


            if ($model->appendTo($parentModel)) {

                if (!BookPermissionSettings::setValues($model->id)) {
                    $transaction->rollBack();
                } else {
                    $data = [
                        "id" => $model->id,
                        "name" => $model->name,
                        "slug" => $model->getUrl(),
                        "description" => $model->description,
                        "cover" => $model->getCover(),
                        "counts" => $model->getBookCounts()
                    ];

                    $transaction->commit();

                    return $this->success($data, 201);
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            return $this->failure($e->getMessage());
        }

        return $this->failure();
    }


    public function actionUpdate($book_slug)
    {
        $name = Yii::$app->request->post('name');
        $description = Yii::$app->request->post('description');
        $bookId = $this->parseBookId($book_slug);
        $bookOtherSettings = Book::getOtherSettings();
        $modelUser = Yii::$app->getUser()->identity;

        $model = Book::find()->where(['id' => $bookId])->one();

        if (empty($model)) {
            return $this->failure("Book does not exists");
        }

        /**@var \app\modules\v1\models\book\Book $model */
        if (!$model->canUserUpdate($modelUser)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (!empty($name)) {
            $model->name = $name;
        }

        if (!empty($description)) {
            $model->description = $description;
        }

        if (!empty($bookOtherSettings)) {
            $model->setOtherSettings($bookOtherSettings);
        }

        $model->update();

        BookPermissionSettings::updateValues($model->id);

        $data = [
            "id" => $model->id,
            "name" => $model->name,
            "slug" => $model->url,
            "description" => $model->description,
            "cover" => $model->getCover()
        ];

        return $this->success($data);
    }

    public function actionDelete($book_slug)
    {
        $bookId = $this->parseBookId($book_slug);
        /** @var Book $model */
        $model = Book::find()->where(['id' => $bookId])->one();

        if (empty($model)) {
            return $this->failure("Book does not exists");
        }


        if (!$this->hasOwnerAccessRights(Book::className(), 'author_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model->updateAttributes(['is_moved_to_bin' => 1]);

        return $this->success();
    }

    public function actionRecover($book_slug)
    {
        $bookId = $this->parseBookId($book_slug);
        /** @var Book $model */
        $model = Book::find()->where(['id' => $bookId])->one();

        if (empty($model)) {
            return $this->failure("Book does not exists");
        }


        if (!$this->hasOwnerAccessRights(Book::className(), 'author_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model->updateAttributes(['is_moved_to_bin' => 0]);

        return $this->success();
    }

    public function actionView($book_slug)
    {
        $page = Yii::$app->request->get('stories_page', 1);

        if (!is_numeric($page))
            return $this->failure("Invalid parameter 'page'", 400);

        $bookId = $this->parseBookId($book_slug);
        /** @var Book $model */
        $model = Book::find()->where(['id' => $bookId])->one();

        $modelUser = User::findOne(['id' => $model->author_id, 'status' => User::STATUS_ACTIVE]);

        if (empty($modelUser)) {
            return $this->failure("User does not found");
        }

        if ($book_slug == 'bin') {
            $model = new Book();
            $data = $model->addBinTree($modelUser->id);
        } else {

            if ($model == null) {
                return $this->failure("Box doesn't exist");
            }

            if ($model !== null && $model->isExistenceVisibile()) {
                $model->setPage($page);
                $model->setItemsPerPage(10);

                $stories = $model->isContentVisible() ? $model->getBookStories() : [];

                $data = [
                    "id" => $model->id,
                    "name" => $model->name,
                    "slug" => $model->url,
                    "description" => $model->description,
                    'cover' => $model->getCover(),
                    "counts" => [
                        "stories" => (int)StoryBook::find()
                            ->leftJoin('story', 'story_book.story_id = story.id')
                            ->leftJoin('story_permission_settings sps', 'story_book.story_id = sps.story_id')
                            ->where([
                                'story_book.book_id' => $model->id,
                                "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC
                            ])->count(),
                        "sub_books" => (int)$model->children(1)->count(),
                        "followers" => (int)FollowBook::find()->where(['book_id' => $model->id])->count(),
                        "images" => (int)StoryFiles::find()->alias('sf')
                            ->innerJoin('story_book sb', 'sf.story_id = sb.story_id')
                            ->innerJoin('story_permission_settings sps', 'sf.story_id = sps.story_id')
                            ->where(['sb.book_id' => $model->id, "sps.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC])
                            ->count(),
                        'knockers' => (int)KnockBook::find()->where(['book_id' => $model->id])->count()
                    ],
                    "stories" => $stories,
                    "settings" => $model->getSettings()
                ];
            }

        }
        return $this->success($data);
    }

    public function actionMove($book_slug)
    {
        $bookBeforeSlug = Yii::$app->request->post('book_before_slug');
        $bookParentSlug = Yii::$app->request->post('book_parent_slug');

        $bookId = $this->parseBookId($book_slug);

        $model = Book::find()->where(['id' => $bookId])->one();
        if ($model === null) {
            return $this->failure("Book does not exists");
        }

        if ($model->is_default == 1) {
            return $this->failure("Cannot move Wallbook", 422);
        }

        if (!$this->hasOwnerAccessRights(Book::className(), 'author_id', $bookId)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (!empty($bookBeforeSlug)) {
            $insertBeforeId = $this->parseBookId($bookBeforeSlug);

            if (!$this->hasOwnerAccessRights(Book::className(), 'author_id', $insertBeforeId)) {
                return $this->failure("You are not allowed to perform this action", 401);
            }


            $neighborModel = Book::find()->where([
                'id' => $insertBeforeId,
                'author_id' => Yii::$app->user->id
            ])->one();

            if ($neighborModel->is_default == 1) {
                return $this->failure("Wallbook must be the first", 400);
            }


            if ($neighborModel == null) {
                return $this->failure("Parent book does not exists");
            }


            $model->insertBefore($neighborModel);

            return $this->success();
        }

        if (!empty($bookParentSlug)) {
            $bookParentId = $this->parseBookId($bookParentSlug);

            if (!$this->hasOwnerAccessRights(Book::className(), 'author_id', $bookParentId)) {
                return $this->failure("You are not allowed to perform this action", 401);
            }


            $parentModel = Book::find()->where([
                'id' => $bookParentId,
                'author_id' => Yii::$app->user->id
            ])->one();

            if ($parentModel->is_default == 1) {
                return $this->failure("Wallbook must be the first", 400);
            }

            if ($parentModel == null) {
                return $this->failure("Parent book does not exists");
            }


            $model->appendTo($parentModel);

            return $this->success();
        }

        return $this->failure();
    }

    private function parseBookId($string)
    {
        $array = explode(Book::URLDELIMITER, $string);

        return $array[0];
    }

    public function actionValuesForOptions()
    {
        return $this->success(BookPermissionSettings::optionsForAccessSettings());
    }

}