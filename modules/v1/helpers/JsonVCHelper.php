<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/carlositos
 */

namespace app\modules\v1\helpers;

use app\modules\v1\models\identity\Identity;
use yii\helpers\Json;
use Yii;

class JsonVCHelper
{
    public static function changeIdentityForDescription($json)
    {
        $data = Json::decode($json, true);

        if(isset($data['claim']) && isset($data['claim']['description']) && !empty($data['claim']['description'])) {
            $identity = Yii::$app->user->identity->getSlug();
            $data['claim']['description'] = "I, Validbook identity – {$identity}, referred to as the \"Signer\", by signing this statement certify, that I have read, understand and agree to comply with all terms and conditions written in the digital file \"\", \r\n\treferred to as the \"Signed File\". The Signed File is uniquely identified by the hash value: SHA3-256 = \"\"";
        }

        return Json::encode($data);
    }
}