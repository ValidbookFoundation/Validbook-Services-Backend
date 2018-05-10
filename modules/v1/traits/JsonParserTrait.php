<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/carlositos
 */

namespace app\modules\v1\traits;

use app\modules\v1\models\identity\Identity;
use app\modules\v1\traits\GethClientTrait;
use yii\helpers\Json;
use Yii;

trait JsonParserTrait
{
    use GethClientTrait;

    public $json;
    public $signature;
    public $address;

    public function setJson($json)
    {
        $this->json = $json;
    }

    public function getJson()
    {
        return $this->json;
    }

    public function getSignatureFromMessage()
    {
        $data = Json::decode($this->getJson(), false);

        if(isset($data->proof) && isset($data->proof->signatureValue) && !empty($data->proof->signatureValue)) {
            return $data->proof->signatureValue;
        }

        return null;
    }

    public function getTitleFromMessage()
    {
        $data = Json::decode($this->getJson(), false);

        if(isset($data->claim) && isset($data->claim->title))
            return $data->claim->title;

        return null;
    }

    public function getPublicAddressFromMessage()
    {
        $data = Json::decode($this->getJson(), false);

        if(isset($data->proof) && isset($data->proof->creator) && !empty($data->proof->creator)) {
            $creator = $data->proof->creator;

            $pieces = explode(Yii::$app->params['validbookVCId'], $creator);
            if(isset($pieces[1]) && !empty($pieces[1])) {
                $creator = $pieces[1];

                $identity = Identity::find()->where(['identity' => $creator])->asArray()->one();
                if($identity !== null) {
                    return mb_strtolower($identity['public_address']);
                }
            }
        }

        return null;
    }

    public function getChangeIdentityForDescription()
    {
        $data = Json::decode($this->getJson(), true);

        if(isset($data['claim']) && isset($data['claim']['description']) && !empty($data['claim']['description'])) {
            $identity = Yii::$app->user->identity->getSlug();
            $data['claim']['description'] = "I, Validbook identity – {$identity}, referred to as the \"Signer\", by signing this statement certify, that I have read, understand and agree to comply with all terms and conditions written in the digital file \"\", \r\n\treferred to as the \"Signed File\". The Signed File is uniquely identified by the hash value: SHA3-256 = \"\"";
        }

        return Json::encode($data);
    }

    public function getMessageWithoutProof()
    {
        $data = Json::decode($this->getJson(), true);

        if(isset($data['proof'])) {
            unset($data['proof']);
        }

        return Json::encode($data);
    }

    public function hasProof()
    {
        $data = Json::decode($this->getJson(), false);

        if(isset($data->proof) && !empty($data->proof)) {
            return true;
        }

        return false;
    }

    public function isJSON()
    {
        $string = $this->getJson();
        return is_string($string) &&
        is_array(json_decode($string, true)) &&
        (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public function getUuid()
    {
        $data = Json::decode($this->getJson(), false);

        if(isset($data->uuid) && !empty($data->uuid))
            return $data->uuid;

        return null;
    }

    public function getUuidFromClaim()
    {
        $data = Json::decode($this->getJson(), false);

        if(isset($data->claim) && !empty($data->claim) && isset($data->claim->uuid) && !empty($data->claim->uuid))
            return $data->claim->uuid;

        return null;
    }

    public function isVerified()
    {
        $messageWithoutProof = $this->getMessageWithoutProof();
        $hexMessage = $this->hexMessage($messageWithoutProof);

        return $this->verifySig($hexMessage, $this->signature, [$this->address]);
    }
}