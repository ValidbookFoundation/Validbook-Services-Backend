<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\Profile;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\story\StoryFiles;
use app\modules\v1\models\User;
use app\modules\v1\models\UserPhoto;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class PhotoController
 * @package app\modules\v1\controllers
 */
class PhotoController extends Controller
{
    public function actionView()
    {
        $userId = Yii::$app->request->get('user_id');

        $modelUser = \Yii::$app->getUser()->getId();

        $user = User::findOne(['id' => $userId, 'status' => User::STATUS_ACTIVE]);

        if ($user == null) {
            return $this->failure("User does not exist");
        }

        $page = Yii::$app->request->get('page', 1);

        $storyIds = [];

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }


        if ($modelUser == $userId) {
            $stories = Story::find()
                ->innerJoin('story_book', 'story.id=story_book.story_id')
                ->where(['story.user_id' => $userId, 'story_book.is_moved_to_bin' => 0])
                ->all();

            $storyIds = ArrayHelper::getColumn($stories, 'id');

        } else {

            $books = Book::findAll(['author_id' => $userId, 'is_moved_to_bin' => 0]);
            foreach ($books as $book) {
                $storyIds += $book->getAllowedStoriesIds();
            }
        }

        $storyIds = array_unique($storyIds);


        $sFile = new StoryFiles();
        $sFile->setPage($page);
        $sPhotos = $sFile->getAllImages($storyIds);

        $uFile = new UserPhoto();
        $uFile->setPage($page);
        $uPhotos = $uFile->getAllImages($userId);


        $result = array_merge($sPhotos, $uPhotos);

        usort($result, function ($a, $b) {
            return $a['created'] > $b['created'] ? -1 : +1;
        });


        return $this->success(array_slice($result, 0, 20));
    }

    public function actionCover()
    {
        $page = Yii::$app->request->get('page', 1);
        $userId = Yii::$app->request->get('user_id');

        $user = User::findOne(['id' => $userId, 'status' => User::STATUS_ACTIVE]);

        if ($user == null) {
            return $this->failure("User does not exist");
        }

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $uFile = new UserPhoto();
        $uFile->setPage($page);
        $uPhotos = $uFile->getImagesForType($userId, UserPhoto::TYPE_COVER);

        return $this->success($uPhotos);

    }

    public function actionAvatar()
    {
        $page = Yii::$app->request->get('page', 1);
        $userId = Yii::$app->request->get('user_id');

        $user = User::findOne(['id' => $userId, 'status' => User::STATUS_ACTIVE]);

        if ($user == null) {
            return $this->failure("User does not exist");
        }

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $uFile = new UserPhoto();
        $uFile->setPage($page);
        $uPhotos = $uFile->getImagesForType($userId, UserPhoto::TYPE_AVATAR);

        return $this->success($uPhotos);
    }

    public function actionBook()
    {
        $page = Yii::$app->request->get('page', 1);
        $userId = Yii::$app->request->get('user_id');
        $bookId = Yii::$app->request->get('book_id');

        $storyIds = [];

        $modelUser = \Yii::$app->getUser()->getId();

        $user = User::findOne(['id' => $userId, 'status' => User::STATUS_ACTIVE]);

        $modelBook = Book::findOne(['id' => $bookId, 'is_moved_to_bin' => 0]);

        if ($user == null) {
            return $this->failure("User does not exist");
        }

        if ($modelBook == null) {
            return $this->failure("Book does not exist");
        }

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $childBooks = $modelBook->children()->all();
        $childBooks[] = $modelBook;

        $bookIds = ArrayHelper::getColumn($childBooks, 'id');
        if ($modelUser == $userId) {
            $stories = Story::find()
                ->innerJoin('story_book', 'story.id=story_book.story_id')
                ->where(['story.user_id' => $userId, 'story_book.is_moved_to_bin' => 0, 'story_book.book_id' => $bookIds])
                ->all();

            $storyIds = ArrayHelper::getColumn($stories, 'id');
        } else {
            /** @var Book $book */
            foreach ($childBooks as $book) {
                $storyIds += $book->getAllowedStoriesIds();
            }
        }

        $storyIds = array_unique($storyIds);


        $sFile = new StoryFiles();
        $sFile->setPage($page);
        $sPhotos = $sFile->getAllImages($storyIds);

        return $this->success($sPhotos);
    }

    public function actionDelete($id)
    {
        $entity = Yii::$app->request->post('entity');
        $entityId = Yii::$app->request->post('entity_id');

        if ($entity == null) {
            return $this->failure("Entity can not be empty", 422);
        }

        if ($entityId == null) {
            return $this->failure("id can not be empty", 422);
        }

        $modelEntity = Profile::findOne(['user_id' => $entityId]);

        switch ($entity) {
            case UserPhoto::USER_ENTITY:
                $model = UserPhoto::findOne($id);

                break;
            case StoryFiles::STORY_ENTITY:
                $model = StoryFiles::findOne($id);
                break;
            default:
                $model = null;

        }

        if ($model == null) {
            return $this->failure("User does not exist");
        }

        $s3 = Yii::$app->get('s3');

        $awsPath = explode("/", $model->url);
        $awsPath = array_slice($awsPath, 4);
        $awsPath = implode("/", $awsPath);

        $result = $s3->commands()->delete($awsPath)->execute();

        if ($result) {
            if ($model->type == UserPhoto::TYPE_AVATAR) {
                $modelEntity->avatar = null;
                $modelEntity->update();
            } elseif ($model->type == UserPhoto::USER_ENTITY) {
                $modelEntity->cover = null;
                $modelEntity->update();
            }
            $model->delete();

            return $this->success();
        }

        return $this->failure('Unexpected error', 500);

    }

}