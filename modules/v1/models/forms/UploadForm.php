<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\forms;


use app\modules\v1\helpers\ImageHelper;
use app\modules\v1\models\story\StoryFiles;
use app\modules\v1\models\story\StoryImageSize;
use Imagine\Image\Box;
use Yii;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;

class UploadForm extends Model
{

    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'extensions' => 'png, jpg, txt, pdf', 'maxFiles' => 4],
        ];
    }

    public function upload(UploadedFile $file)
    {
        $filePath = Yii::getAlias('@runtime') . "/" . $file->baseName . '.' . $file->extension;
        $file->saveAs($filePath);
        return $filePath;
    }

    public function uploadStoryFiles($storyId, $sizes)
    {
        $result = [];

        foreach ($this->files as $key => $file) {
            $originalFilePath = $this->upload($file);
            $fileName = Yii::$app->security->generateRandomString();
            switch ($file->type) {
                case 'image/jpeg':
                    $type = 'story-images';
                    break;
                case 'image/png':
                    $type = 'story-images';
                    break;
                case 'text/plain':
                    $type = 'story-files';
                    break;
                default:
                    $type = 'story-images';
            }

//            //check size

//            if ($file->type == 'image/jpeg' || $file->type == 'image/png') {
//                $checkSize = $this->checkImageSize($filePath, $sizes[$key]);
//                $filePath = $checkSize['filePath'];
//                $sizes[$key]['original'] = $checkSize['original'];
//            }

            $bucket = $this->transferAws($originalFilePath, $fileName, $type, '.' . $file->extension);

            $storyFile = new StoryFiles();
            $storyFile->story_id = $storyId;
            $storyFile->type = $file->type;
            $storyFile->url = $bucket['ObjectURL'];
            $storyFile->etag = $bucket['ETag'];
            $storyFile->save();

            if ($storyFile->type == 'image/jpeg' || $storyFile->type == 'image/png') {

                if ($key <= 5) {
                    $this->saveImageThumbs($storyId, $originalFilePath, $fileName, $file->type, '.' . $file->extension, $storyFile->id, $sizes[$key]);
                }

                $checkSize = $this->checkImageSize($originalFilePath, $sizes[$key]);

                $filePath = $checkSize['filePath'];

                $bucket = $this->transferAws($filePath, $fileName, $type, '.' . $file->extension);

                $storyFile->url = $bucket['ObjectURL'];
                $storyFile->update();


                $sizes[$key]['original'] = $checkSize['original'];
            }

            //remove file from server
            unlink($originalFilePath);


            $result[] = $storyFile->getFormattedCard();
        }
        return $result;
    }

    private function transferAws($filePath, $fileName, $type = "stories", $fileExt = '.jpg')
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $userId = Yii::$app->user->id;

        //if userId is null (for book cover, story images)
        $awsPath = $type . '/' . date("Y") . '/' . date("m") . '/' . date("d") . '/' . $userId . '/' . $fileName . $fileExt;

        $result = $s3->commands()->upload($awsPath, $filePath)->execute();

        return $result;
    }

    private function saveImageThumbs($storyId, $filePath, $fileName, $fileType, $fileExtension, $originalId, $sizes)
    {
        $options = ['jpeg_quality' => 100, 'format' => 'jpeg'];

        if (!empty($sizes)) {
            foreach ($sizes as $key => $size) {

                $newSize = ImageHelper::addSmallSizes($key, $size);

                Image::getImagine()->open($filePath)
                    ->resize(new Box($newSize['width'], $newSize['height']))
                    ->save($filePath, $options);

                $fileName = $size . '-' . $fileName;
                $bucket = $this->transferAws($filePath, $fileName, $fileType, $fileExtension);

                $storyImage = new StoryImageSize();
                $storyImage->model_id = $storyId;
                $storyImage->original_id = $originalId;
                $storyImage->url = $bucket['ObjectURL'];
                $storyImage->type = $key == 'original' ? 'small' : $key;
                $storyImage->size = $size;
                $storyImage->save();
            }
        }
    }

    private function checkImageSize($filePath, $sizes)
    {
        $options = ['jpeg_quality' => 100, 'format' => 'jpeg'];

        $newSize = ImageHelper::checkOriginalSize(ImageHelper::KEY_ORIGINAL, $sizes[ImageHelper::KEY_ORIGINAL]);

        if (($newSize['width'] < ImageHelper::MAX_SIZE) && ($newSize['height'] < ImageHelper::MAX_SIZE)) {

        } else {
            Image::getImagine()->open($filePath)
                ->resize(new Box($newSize['width'], $newSize['height']))
                ->save($filePath, $options);
        }

        return ['filePath' => $filePath, 'original' => $newSize['width'] . 'x' . $newSize['height']];
    }
}