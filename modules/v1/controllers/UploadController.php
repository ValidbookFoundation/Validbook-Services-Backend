<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\Avatar;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\Cover;
use app\modules\v1\models\forms\UploadCoverAvatarForm;
use app\modules\v1\models\forms\UploadForm;
use app\modules\v1\models\Profile;
use Yii;
use yii\web\UploadedFile;

/**
 * Class UploadController
 * @package app\modules\v1\controllers
 */
class UploadController extends Controller
{

    const QUALITY_AVATAR = 100;
    const QUALITY_COVER = 50;
    const QUALITY_IMAGE = 50;

    public function actionAvatar()
    {
        $files = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (empty($files)) {
            return $this->failure("File can not be empty", 422);
        }

        $response = [];

        $userId = Yii::$app->user->id;

        /** @var Profile $userModel */
        $userModel = Profile::find()->where(['user_id' => $userId])->one();

        if (empty($userModel)) {
            return $this->failure("User does not exists");
        }

        $modelFile = new UploadCoverAvatarForm();

        $modelFile->files = UploadedFile::getInstancesByName('file');

        $sizes = json_decode(Yii::$app->request->post('image_size'), true);

        $modelFile->uploadAvatar($userModel, "avatars", $sizes);


        $avatars = Avatar::findAll(['user_id' => $userModel->user_id]);

        foreach ($avatars as $avatar) {
            $response = [
                "avatar" . $avatar->size => $avatar->url
            ];
        }

        return $this->success($response);
    }

    public function actionUserCover()
    {
        $userId = Yii::$app->user->id;
        $color = Yii::$app->request->post('color');
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        /** @var Profile $userModel */
        $userModel = Profile::find()->where(['user_id' => $userId])->one();
        if (empty($userModel)) {
            return $this->failure("User does not exists");
        }

        if (empty($file) and empty($color)) {
            return $this->failure("Params picture or color cannot be empty", 401);
        } elseif (!empty($file) and !empty($color)) {
            return $this->failure("Only one param can be fulled", 401);
        }

        if (!empty($file)) {
            $sizes = json_decode(Yii::$app->request->post('image_size'), true);
            if (empty($sizes)) {
                return $this->failure("image_size can not be empty", 422);
            }

            $modelFile = new UploadCoverAvatarForm();

            $modelFile->file = UploadedFile::getInstanceByName('file');
            $modelFile->uploadCover($userModel, "user-covers", $sizes);

            $cover = Cover::findOne(['model_id' => $userId, 'is_actual' => true, 'type' => Cover::USER_TYPE]);

            $response = [
                "picture_original" => $cover->getUrl(),
                "picture_small" => null,
                "color" => null
            ];
        } else {
            $userModel->cover = $color;
            if (!$userModel->save()) {
                return $this->failure($userModel->errors);
            }

            $response = [
                "picture_original" => null,
                "picture_small" => null,
                "color" => $color
            ];
        }

        return $this->success($response, 201);
    }

    public function actionBookCover()
    {
        $bookId = Yii::$app->request->post('book_id');
        $color = Yii::$app->request->post('color');
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        $book = Book::findOne($bookId);

        if (empty($file) and empty($color)) {
            return $this->failure("Params picture or color cannot be empty", 401);
        } elseif (!empty($file) and !empty($color)) {
            return $this->failure("Only one param can be fulled", 401);
        }

        if (!empty($file)) {

            $modelFile = new UploadCoverAvatarForm();

            $sizes = json_decode(Yii::$app->request->post('image_size'));
            if (empty($sizes)) {
                return $this->failure("image_size can not be empty", 422);
            }

            $modelFile->file = UploadedFile::getInstanceByName('file');
            $modelFile->uploadCover($book, "book-covers", $sizes);

            $covers = Cover::findAll(['model_id' => $book->id, 'is_actual' => true, 'type' => Cover::BOOK_TYPE]);


            $response = [
                "picture_original" => $covers[0]->getUrl(),
                "picture_small" => $covers[1]->getUrl(),
                "color" => null
            ];
        } else {
            $book->cover = $color;
            if (!$book->update()) {
                return $this->failure($book->errors);
            }
            $response = [
                "picture_original" => null,
                "picture_small" => null,
                "color" => $color
            ];
        }

        return $this->success($response, 201);
    }

    public function actionStoryImage()
    {
        $response = [];

        $model = new UploadForm();
        $storyId = Yii::$app->request->post('story_id');

        $model->files = UploadedFile::getInstancesByName('file');

        //  $response[] = $model->uploadStoryFiles($storyId);

        return $this->success($response, 201);
    }
}