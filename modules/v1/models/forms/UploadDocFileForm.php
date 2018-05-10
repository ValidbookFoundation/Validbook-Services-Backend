<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\forms;


use app\modules\v1\models\doc\DocumentFile;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadDocFileForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'extensions' => 'png, jpg, txt, pdf, zip', 'maxSize' => 1024 * 1024 * 20],
        ];
    }

    public function upload(UploadedFile $file)
    {
        $filePath = Yii::getAlias('@runtime') . "/" . $file->baseName . '.' . $file->extension;
        $file->saveAs($filePath);
        return $filePath;
    }

    public function uploadFile($docId)
    {
        $model = new DocumentFile();
        $filePath = $this->upload($this->file);
        $fileName = $model->hashFile($filePath);

        $bucket = $this->transferAws($docId, $filePath, $fileName, '.' . $this->file->extension);

        $model->title = $this->file->name;
        $model->type = $this->file->extension;
        $model->doc_id = $docId;
        $model->url = $bucket['ObjectURL'];
        $model->hash = $model->hashFile($bucket['ObjectURL']);
        if ($model->save()) {
            //remove file from server
            unlink($filePath);
            return $model->url;
        } else {
            return null;
        }
    }

    private function transferAws($docId, $filePath, $fileName, $fileExt = '.jpg')
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $userId = Yii::$app->user->id;

        //if userId is null (for book cover, story images)
        $awsPath = $userId . '/documents/' . $docId . '/files/' . $fileName . $fileExt;

        $result = $s3->commands()->upload($awsPath, $filePath)->execute();

        return $result;
    }
}