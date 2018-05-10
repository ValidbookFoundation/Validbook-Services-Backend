<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use app\modules\v1\models\forms\UploadCoverAvatarForm;
use Imagine\Image\Box;
use Yii;
use yii\console\Controller;
use yii\imagine\Image;

class AvatarsResizeController extends Controller
{
    public function actionIndex()
    {
        $connection = Yii::$app->db;

        $sql = "SELECT u.id, p.avatar 
                FROM user u
                LEFT JOIN profile p ON p.user_id = u.id";
        $users = $connection->createCommand($sql)->queryAll();

        foreach ($users as $user) {
            if (!empty($user['avatar'])) {
                $avatarUrl = $user['avatar'];
                $pieces = explode("/avatars/", $avatarUrl);

                $pieces = explode("/" . $user['id'] . "/", $pieces[1]);
                $avatarFolder = "avatars/" . $pieces[0] . "/" . $user['id'];

                $model = new UploadCoverAvatarForm();

                foreach ($model->getAvatarSizes() as $size) {

                    $pieces = explode("x", $size);

                    $newWidth = $pieces[0];
                    $newHeight = $pieces[1];
                    $savePath = Yii::getAlias('@runtime/thumb-test-photo.jpg');

                    Image::getImagine()->open($avatarUrl)
                        ->thumbnail(new Box($newWidth, $newHeight))
                        ->save($savePath, ['quality' => 100]);
                    $filename = Yii::$app->security->generateRandomString(7);

                    $bucket = $this->transferAws($savePath, $filename, $avatarFolder);

                    $sqlUpdate = "INSERT INTO avatar_size
                                              SET
                                                user_id = '{$user['id']}',
                                                size = '{$size}',
                                                url = '{$bucket['ObjectURL']}'";
                    $connection->createCommand($sqlUpdate)->execute();
                }
            }
        }
    }

    private function transferAws($filePath, $fileName, $awsFolder)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $awsPath = $awsFolder . '/' . $fileName . '.jpg';

        $result = $s3->commands()->upload($awsPath, $filePath)->execute();

        return $result;
    }
}