<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\forms;

use app\modules\v1\helpers\FileContentHelper;
use app\modules\v1\models\doc\DocSignature;
use app\modules\v1\models\doc\Document;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class UploadDocForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'extensions' => 'md', 'maxSize' => 1024 * 1024 * 20],
        ];
    }

    public function upload(UploadedFile $file)
    {
        $filePath = Yii::getAlias('@runtime') . "/" . $file->baseName . '.' . $file->extension;
        $file->saveAs($filePath);
        return $filePath;
    }

    public function uploadDoc(Document $doc)
    {
        $filePath = $this->upload($this->file);
        $fileName = $this->file->baseName;

        $fileContent = FileContentHelper::getContent($filePath);

        $strDocContentStart = strpos($fileContent, "<?--- (((((START TEXT))))) ---?>") + strlen("<?--- (((((START TEXT))))) ---?>");
        $strDocContentEnd = strpos($fileContent, "<?--- (((((END TEXT))))) ---?>");

        $doc->content = trim(substr($fileContent, $strDocContentStart, $strDocContentEnd - $strDocContentStart));

        $doc->icon = $doc->setPreviewImage();

        $temp = tmpfile();
        fwrite($temp, $doc->content);

        $bucketDoc = $this->transferAws($doc->id, $temp, $fileName . '.md');

        $doc->url = $bucketDoc['ObjectURL'];

        $strSystemCommStart = strpos($fileContent, "<?--- (((((START PROPERTIES))))) ---?>") + strlen("<?--- (((((START PROPERTIES))))) ---?>");
        $strSystemCommEnd = strpos($fileContent, "<?--- (((((END PROPERTIES))))) ---?>");

        $properties = substr($fileContent, $strSystemCommStart, $strSystemCommEnd - $strSystemCommStart);

        if (!empty($properties)) {
            $properties = str_replace("<?---", null, $properties);
            $properties = str_replace("---?>", null, $properties);
            $properties = json_decode($properties, true);
        }

        if (isset($properties['documentNonce'])) {
            $doc->hash = $doc->hashMessage($doc->content . $properties['documentNonce']);
            $doc->nonce = $properties['documentNonce'];
        }

        if (isset($properties['signatures'])) {
            $doc->is_open_for_sig = 1;

            foreach ($properties['signatures'] as $value) {
                $model = new         $signature = Yii::$app->request->post('signature');DocSignature();
                $model->upload($value, $doc);
            }

            $doc->is_signed = 1;
        }


        if ($doc->update()) {
            //remove file from server
            unlink($filePath);
            return true;
        }

        return false;

    }

    private function transferAws($docId, $filePath, $fileName)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $userId = Yii::$app->user->id;

        //if userId is null (for book cover, story images)
        $awsPath = $userId . '/documents/' . $docId . '/' . $fileName;

        $result = $s3->commands()->upload($awsPath, $filePath)->execute();

        return $result;
    }
}