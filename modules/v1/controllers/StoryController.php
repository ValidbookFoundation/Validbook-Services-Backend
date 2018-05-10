<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\jobs\BlockedStoryInChannelsAfterLogJob;
use app\modules\v1\jobs\FillStoryInChannelsAfterLogJob;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\book\LoggedBook;
use app\modules\v1\models\forms\UploadForm;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\story\StoryBook;
use app\modules\v1\models\story\StoryPermissionSettings;
use Yii;
use yii\db\Query;
use yii\db\Transaction;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */
class StoryController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = array_merge($behaviors['authenticator']['except'], ['view']);

        return $behaviors;
    }

    public function actionView($id)
    {
        $page = Yii::$app->request->get('comments_page', 1);

        $story = Story::findOne($id);
        $story->setPage($page);


        if ($story !== null && $story->isVisibleForUser()) {
            //only array can be formatted
            return $this->success(Story::format([$story], 'story'));
        } else
            return $this->failure("Access denied", 403);
    }


    public function actionCreate()
    {
        //books - array of book slugs. For example ([1-interests, 2-sport, 3-karma])
        $bookSlugs = Yii::$app->request->post('books', []);
        if (empty($bookSlugs)) {
            return $this->failure("books can not be empty", 422);
        }else{
            $bookSlugs = json_decode($bookSlugs);
        }

        $modelUserId = Yii::$app->getUser()->getId();

        $description = Yii::$app->request->post('description', '');

        $sizes  = Yii::$app->request->post("image_sizes");

        $sizes = json_decode($sizes, true);

        //loudness params
        $inStoryline = Yii::$app->request->post('in_storyline', 1);
        $inChannels = Yii::$app->request->post('in_channels', 1);
        $inBooks = Yii::$app->request->post('in_books', 1);

        //if "Loud logging" checked, "Loud in book" must me checked as default
        if ($inChannels == 1) {
            $inBooks = 1;
        }

        if (empty($bookSlugs) || !is_array($bookSlugs)) {
            return $this->failure("books must be an array", 422);
        }


        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {

            $model = new Story([
                'description' => $description,
                'in_storyline' => $inStoryline,
                'in_channels' => $inChannels,
                'in_book' => $inBooks,
            ]);

            if ($model->save() && StoryPermissionSettings::setValues($model->id)) {
                //log story to books
                foreach ($bookSlugs as $bookSlug) {

                    $bookId = $this->parseBookId($bookSlug);
                    /** @var \app\modules\v1\models\book\Book $book */
                    $book = Book::find()->where(['id' => $bookId, 'is_moved_to_bin' => 0])->one();

                    if (!empty($book)) {
                        if ($book->auto_export == 1) {
                            $childrenBooks = $book->children(1)->all();
                            /** @var \app\modules\v1\models\book\Book $cBook */
                            foreach ($childrenBooks as $cBook) {
                                if (!in_array($cBook->getUrl(), $bookSlugs)) {
                                    $storyBook = new StoryBook([
                                        'book_id' => $cBook->id,
                                        'story_id' => $model->id
                                    ]);
                                    if ($storyBook->canUserAddStory()) {
                                        $storyBook->save();
                                        //queue
                                        Yii::$app->queue->push(new FillStoryInChannelsAfterLogJob([
                                            'userId' => $modelUserId,
                                            'bookId' => $storyBook->book_id,
                                            'storyId' => $storyBook->story_id,
                                            'date' => $storyBook->created_at
                                        ]));
                                    }
                                }
                            }
                        }
                        if ($book->auto_import == 1) {
                            $parentBook = $book->parents(1)->one();
                            /** @var \app\modules\v1\models\book\Book $parentBook */
                            if (!in_array($parentBook->getUrl(), $bookSlugs)) {
                                $storyBook = new StoryBook([
                                    'book_id' => $parentBook->id,
                                    'story_id' => $model->id
                                ]);
                                if ($storyBook->canUserAddStory()) {
                                    $storyBook->save();
                                    //queue
                                    Yii::$app->queue->push(new FillStoryInChannelsAfterLogJob([
                                        'userId' => $modelUserId,
                                        'bookId' => $storyBook->book_id,
                                        'storyId' => $storyBook->story_id,
                                        'date' => $storyBook->created_at
                                    ]));
                                }
                            }
                        }

                        $storyBook = new StoryBook([
                            'book_id' => $book->id,
                            'story_id' => $model->id
                        ]);
                        //is user can post story to book
                        if ($storyBook->canUserAddStory()) {
                            $storyBook->save();
                            //queue
                            Yii::$app->queue->push(new FillStoryInChannelsAfterLogJob([
                                'userId' => $modelUserId,
                                'bookId' => $storyBook->book_id,
                                'storyId' => $storyBook->story_id,
                                'date' => $storyBook->created_at
                            ]));

                        } else {
                            $transaction->rollBack();

                        }
                    } else
                        $transaction->rollBack();
                }

                $transaction->commit();

                $modelFile = new UploadForm();

                $modelFile->files = UploadedFile::getInstancesByName('file');

                if (!empty($modelFile->files)) {
                    $modelFile->uploadStoryFiles($model->id, $sizes);
                }

                return $this->success(Story::format([$model]), 201);
            } else {
                $transaction->rollBack();
            }

        } catch (\Exception $e) {
            $transaction->rollBack();

            return $this->failure($e->getMessage(), $e->getCode());
        }

        return $this->failure();
    }

    public function actionUpdate($id)
    {
        $description = Yii::$app->request->post('description');

        if (!$this->hasOwnerAccessRights(Story::className(), 'user_id', $id))
            return $this->failure("You are not allowed to perform this action", 401);

        $model = Story::findOne($id);

        if (!empty($model)) {

            $model->description = $description;
            if ($model->validate()) {
                $model->update();

                return $this->success(Story::format($id));
            } else {
                // validation failed: $errors is an array containing error messages
                return $this->failure("Story does not exists");
            }
        }
    }

    public function actionDelete($id)
    {
        /** @var Story $model */
        $model = Story::find()->where(['id' => $id])->one();

        if (!empty($model)) {
            if (!$model->canUserDelete()) {
                return $this->failure("You are not allowed to perform this action", 401);
            }
            if ($model->isMovedToBin()) {
                return $this->success();
            }
        } else {
            return $this->failure("Story does not exists");
        }
    }

    private function parseBookId($string)
    {
        $array = explode("-", $string);

        return $array[0];
    }

    public function actionBooksTreeRelog($id)
    {
        $userId = Yii::$app->user->id;
        $model = Story::findOne($id);
        if ($model === null) {
            return $this->failure("Story does not exists");
        }
        if ($userId !== null) {

            $booksData = LoggedBook::getFormattedData($userId, $id);

            return $this->success($booksData);
        }
        return $this->failure("User does not exists");
    }

    public function actionRelog($id)
    {
        $bookSlug = Yii::$app->request->post('book_slug');
        $isLogged = Yii::$app->request->post('is_logged_story');
        $userId = Yii::$app->user->id;

        if (empty($bookSlug)) {
            return $this->failure("book cannot be null", 422);
        }
        if ($isLogged === null) {
            return $this->failure("is_logged_story cannot be null", 422);
        }

        $model = Story::findOne($id);
        if ($model === null) {
            return $this->failure("Story does not exists");
        }

        //check if story is visible and open for this user
        if (!$model->isVisibleForUser()) {
            return $this->failure("You have not access to log this story", 401);
        }


        $bookId = $this->parseBookId($bookSlug);
        /** @var \app\modules\v1\models\book\Book $book */
        $book = Book::find()->where(['id' => $bookId])->one();
        if (!empty($book)) {
            if ($book->is_root == 1) {
                return $this->failure("Cannot write story to root", 422);
            }
            //check if book ids are owned by this user
            if ($book->author->id == $userId) {
                if ($isLogged) {
                    //repost story
                    $storyBook = new StoryBook();
                    $storyBook->story_id = $model->id;
                    $storyBook->book_id = $book->id;

                    $bookItem = [
                        'name' => $book->name,
                        'key' => $book->getUrl(),
                        'icon' => $book->getIcon(),
                        'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/books', 'book_slug' => $book->getUrl()], true),
                        'auto_export' => $book->auto_export,
                        'auto_import' => $book->auto_import,
                        'is_logged_story' => true
                    ];
                    if ($storyBook->save()) {
                        //queue
                        Yii::$app->queue->push(new FillStoryInChannelsAfterLogJob([
                            'userId' => $userId,
                            'bookId' => $storyBook->book_id,
                            'storyId' => $storyBook->story_id,
                            'date' => $storyBook->created_at
                        ]));
                        return $this->success($bookItem);
                    } else {
                        return $this->failure($storyBook->errors);
                    }
                } else {
                    $storyBookCount = (new Query())
                        ->select('count(*) as count')
                        ->from('story_book')
                        ->where(['story_id' => $id])
                        ->one();

                    $storyBook = StoryBook::findOne(['book_id' => $bookId, 'story_id' => $id]);
                    if ($storyBookCount['count'] == 1 && !empty($storyBook)) {
                        $storyBook->is_moved_to_bin = 1;
                        $storyBook->update();

                        // job for making remove stories from followers channels
                        Yii::$app->queue->push(new BlockedStoryInChannelsAfterLogJob([
                            'userId' => $userId,
                            'bookId' => $storyBook->book_id,
                            'storyId' => $storyBook->story_id
                        ]));

                    } elseif ($storyBookCount['count'] > 1 && !empty($storyBook)) {

                        $storyBook->delete();

                    } else {
                        return $this->failure("Story cannot been removed", 422);
                    }

                    $bookItem = [
                        'name' => $book->name,
                        'key' => $book->getUrl(),
                        'icon' => $book->getIcon(),
                        'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/books', 'book_slug' => $book->getUrl()], true),
                        'auto_export' => $book->auto_export,
                        'auto_import' => $book->auto_import,
                        'is_logged_story' => false
                    ];
                    return $this->success($bookItem);
                }
            }
        } else {
            return $this->failure("Book does not exists");
        }
    }

    public function actionVisibilityBooks()
    {
        $bookIds = Yii::$app->request->get('book_ids');
        if (!empty($bookIds)) {
            $bookIds = explode(',', $bookIds);
            $data = StoryPermissionSettings::getBooksVisibility($bookIds);
            return $this->success($data);
        } else {
            return $this->failure("missing parameter book_ids", 400);
        }

    }

    public function actionUpdateVisibility($id)
    {
        $visibilityTypeName = Yii::$app->request->post('visibility');

        $visibilityType  = Story::getVisibilityValue($visibilityTypeName);

        if (!in_array($visibilityType, [0, 1, 2])) {
            return $this->failure("Visibility type is not correct", 422);
        }

        if (!$this->hasOwnerAccessRights(Story::className(), 'user_id', $id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        StoryPermissionSettings::updateValues($id);
        return $this->success();
    }

    public function actionPin($id)
    {
        $pins = Yii::$app->request->post('pins', []);
        if (!is_array($pins) || empty($pins))
            return $this->failure("Pins must be an array");

        foreach ($pins as $pin) {
            $order = $pin['order'];
            $bookId = $pin['book_id'];

            $story = Story::findOne($id);
            if ($story === null) {
                return $this->failure("Story does not exists");
            }

            if (!in_array($bookId, $story->getBooksIDs())) {
                return $this->failure("Story does not belong this book");
            }

            if ($order < 0 || !is_int((int)$order)) {
                return $this->failure("Order parameter must be natural number", 422);
            }

            if (!$this->hasOwnerAccessRights(Story::className(), 'user_id', $id)) {
                return $this->failure("You are not allowed to perform this action", 401);
            }

            /** @var \app\modules\v1\models\story\StoryBook $model */
            $model = StoryBook::find()->where(['story_id' => $id, 'book_id' => $bookId])->one();
            if (!empty($model)) {

                $model->is_pin = 1;
                $model->pin_order = $order;

                $model->update();
            }
        }
        return $this->success();
    }
}