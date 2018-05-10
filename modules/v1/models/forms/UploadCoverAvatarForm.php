<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\forms;


use app\modules\v1\helpers\ImageHelper;
use app\modules\v1\models\Avatar;
use app\modules\v1\models\Cover;
use app\modules\v1\models\UserPhoto;
use Imagine\Image\Box;
use Yii;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class UploadCoverAvatarForm
 * @package app\modules\v1\models\forms
 */
class UploadCoverAvatarForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /** @var  UploadedFile [] */
    public $files;

    protected $book_covers_size = [
        '810x281',
        '597x207'
    ];

    protected $user_cover_size = [
        '1760x220',
        '1920x235'
    ];

    private $_avatarSizes = [
        '32x32',
        '48x48',
        '100x100',
        '220x220',
        '230x230',
    ];

    public function rules()
    {
        return [
            [['file'], 'image', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 4],
            [['files'], 'image', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 4],
        ];
    }

    public function upload(UploadedFile $file)
    {
        $filePath = Yii::getAlias('@runtime') . "/" . $file->baseName . '.jpg';
        $file->saveAs($filePath);
        return $filePath;
    }

    public function uploadCover($model, $type, $sizeOriginal)
    {
        $originFilePath = $this->upload($this->file);
        $quality = [];

        $originFilePath = $this->checkImageSize($originFilePath, $sizeOriginal);

        if ($this->file->type = 'image/jpeg') {
            $quality = ['jpeg_quality' => 100];
        }


        if ($model->getClassName() == "Book") {

            if ($this->file->type = 'image/jpeg') {
                $quality = ['jpeg_quality' => 100];
            }

            $currentCovers = Cover::findAll(['model_id' => $model->id, 'is_actual' => 1]);
            if (!empty($currentCovers)) {
                foreach ($currentCovers as $currentCover) {
                    $currentCover->is_actual = 0;
                    $currentCover->update();
                }
            }
            foreach ($this->book_covers_size as $size) {

                $filePath = Yii::getAlias('@runtime') . "/" . $size . '_' . $this->file->baseName . ".jpg";
                $pieces = explode("x", $size);

                $width = (int)$pieces[0];
                $height = (int)$pieces[1];

                Image::getImagine()->open($originFilePath)->resize(new Box($width, $height))->save($filePath, $quality);
                $bucket = $this->transferAws($type, $filePath, $size . '_' . $this->file->baseName . "_" . time() . ".jpg");

                $saveModel = new Cover();

                $saveModel->type = Cover::BOOK_TYPE;
                $saveModel->model_id = $model->id;
                $saveModel->size = $size;
                $saveModel->url = $bucket['ObjectURL'];
                $saveModel->is_actual = true;
                $saveModel->save();

                unlink($filePath);
            }
        } elseif ($model->getClassName() == "Profile") {

            $bucketOriginal = $this->transferAws($type, $originFilePath, $this->file->baseName . "_" . time() . '.jpg');
            $sizes = $this->user_cover_size;

            $userPhoto = new UserPhoto();
            $userPhoto->type = UserPhoto::TYPE_COVER;
            $userPhoto->user_id = $model->user_id;
            $userPhoto->url = $bucketOriginal['ObjectURL'];
            $userPhoto->save();


            foreach ($sizes as $size) {
                $pieces = explode("x", $size);

                $width = (int)$pieces[0];
                $height = (int)$pieces[1];

                $filePath = Yii::getAlias('@runtime') . "/" . $size . '_' . $this->file->name;

                $box = new Box($width, $height);

                Image::getImagine()->open($originFilePath)->resize($box)->save($filePath, $quality);
                $bucket = $this->transferAws($type, $filePath, $size . '_' . $this->file->baseName . "_" . time() . '.jpg');

                $saveModel = new Cover();

                $currentCovers = Cover::findAll(['model_id' => $model->user_id, 'is_actual' => 1, 'type' => Cover::USER_TYPE]);

                if (!empty($currentCovers)) {
                    foreach ($currentCovers as $currentCover) {
                        $currentCover->is_actual = 0;
                        $currentCover->update();
                    }
                }

                $saveModel->type = Cover::USER_TYPE;
                $saveModel->model_id = $model->user_id;
                $saveModel->size = $size;
                $saveModel->original_id = $userPhoto->id;
                $saveModel->url = $bucket['ObjectURL'];
                $saveModel->is_actual = true;
                $saveModel->save();

                unlink($filePath);
            }

        }

        unlink($originFilePath);

    }

    public function uploadAvatar($userModel, $type, $sizes)
    {
        $originFilePath = $this->upload($this->files[0]);

        $originFilePath = $this->checkImageSize($originFilePath, $sizes);

        $bucketOriginal = $this->transferAws($type, $originFilePath, $this->files[0]->getBaseName() . ".jpg");

        $options = [];

        if ($this->files[0]->type = 'image/jpeg') {
            $options = ['jpeg_quality' => 100];
        }

        Avatar::deleteAll(['user_id' => $userModel->user_id]);

        $userPhoto = new UserPhoto();
        $userPhoto->type = UserPhoto::TYPE_AVATAR;
        $userPhoto->user_id = $userModel->user_id;
        $userPhoto->url = $bucketOriginal['ObjectURL'];
        $userPhoto->save();

        foreach ($this->getAvatarSizes() as $size) {
            $pieces = explode("x", $size);

            $newWidth = $pieces[0];
            $newHeight = $pieces[1];

            $filePath = Yii::getAlias('@runtime') . "/" . $size . '_' . $this->files[0]->getBaseName() . ".jpg";

            Image::getImagine()->open($originFilePath)
                ->resize(new Box($newWidth, $newHeight))
                ->save($filePath, $options);

            $bucket = $this->transferAws($type, $filePath, $size . '_' . $this->files[0]->getBaseName() . "_" . time() . ".jpg");

            $avatarModel = new Avatar();
            $avatarModel->user_id = $userModel->user_id;
            $avatarModel->size = $size;
            $avatarModel->original_id = $userPhoto->id;
            $avatarModel->url = $bucket['ObjectURL'];
            $avatarModel->save();

            unlink($filePath);
        }

        $userModel->avatar = $bucketOriginal['ObjectURL'];
        $userModel->update();


        unlink($originFilePath);
    }

    private function transferAws($type, $filePath, $fileName)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $userId = Yii::$app->user->id;

        //if userId is null (for book cover, story images)
        $awsPath = $userId . '/' . $type . '/' . $fileName;

        $result = $s3->commands()->upload($awsPath, $filePath)->execute();

        return $result;
    }

    public function getAvatarSizes()
    {
        return $this->_avatarSizes;
    }

    private function checkImageSize($originFilePath, $sizes)
    {
        $options = ['jpeg_quality' => 100];

        $newSize = ImageHelper::checkOriginalSize(ImageHelper::KEY_ORIGINAL, $sizes[ImageHelper::KEY_ORIGINAL]);


        if (($newSize['width'] < ImageHelper::MAX_SIZE) && ($newSize['height'] < ImageHelper::MAX_SIZE)) {

        } else {
            Image::getImagine()->open($originFilePath)
                ->resize(new Box($newSize['width'], $newSize['height']))
                ->save($originFilePath, $options);
        }

        return $originFilePath;
    }

}