<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/carlositos
 */

namespace app\modules\v1\models;

use Yii;

class Aws
{
    public static function getAwsUrl($awsPathWithFile, $file)
    {
        $temp = tmpfile();
        fwrite($temp, $file);
        $s3 = Yii::$app->get('s3');

        $result = $s3->commands()->upload($awsPathWithFile, $temp)->execute();

        if (!empty($result)) {
            fclose($temp);

            if($result['ObjectURL'])
                return $result['ObjectURL'];
        }

        return null;
    }
}