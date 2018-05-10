<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use Imagine\Image\Box;
use Yii;
use yii\console\Controller;
use yii\imagine\Image;

class DefaultAvatarController extends Controller
{
    public function actionIndex()
    {
        //add your path
        $path = '';
        $defAvatars = [
            '32' => $path . 'user32.png',
            '48' => $path . 'user48.png',
            '100' => $path . 'user100.png',
            '230' => $path . 'user230.png'
        ];

        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        foreach ($defAvatars as $key => $filePath) {
            $savePath = Yii::getAlias('@runtime/thumb-test-photo.jpg');

            Image::getImagine()->open($filePath)
                ->thumbnail(new Box($key, $key))
                ->save($savePath, ['png_compression_level' => 0]);
            //if userId is null (for book cover, story images)
            $awsPath = 'default-avatars/user-' . $key . '.png';
            $result = $s3->commands()->upload($awsPath, $savePath)->execute();
        }
    }

}