<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;

use app\modules\v1\models\User;
use app\modules\v1\models\UserKey;
use app\modules\v1\traits\GethClientTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "signature".
 *
 * @property integer $id
 * @property integer $document_id
 * @property string $public_address
 * @property string $sig
 * @property string $message
 * @property string $hash
 * @property string $short_sig_link
 * @property string $long_sig_link
 * @property integer $created_at
 * @property integer $is_revoked
 * @property integer $is_voided
 */
class DocSignature extends ActiveRecord
{

    use GethClientTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doc_signature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'is_voided', 'is_revoked', 'document_id'], 'integer'],
            [['public_address', 'document_id', 'sig', 'message', 'short_sig_link', 'long_sig_link'], 'required'],
            [['message', 'hash'], 'string'],
            [['public_address', 'sig', 'long_sig_link', 'short_sig_link'], 'string', 'max' => 255],
        ];
    }

    public function saveFileSignature($awsPath)
    {
        $fileNameShort = 'sf_signature_' . $this->public_address;
        $fileNameLong = 'lg_signature_' . $this->public_address;


        $signatureTemplate = new SignatureTemplate($this->public_address, $this->message, $this->hash, $this->sig,
            Yii::$app->formatter->asDatetime($this->created_at, 'dd-MMM-yyyy HH:MM:ss'));


        $tempShort = tmpfile();
        $tempLong = tmpfile();
        fwrite($tempLong, $signatureTemplate->getDoc());
        fwrite($tempShort, $signatureTemplate->getShortDoc());

        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        $pathShort = $awsPath . $fileNameShort . '.md';
        $pathLong = $awsPath . $fileNameLong . '.md';

        $resultShort = $s3->commands()->upload($pathShort, $tempShort)->execute();
        $resultLong = $s3->commands()->upload($pathLong, $tempLong)->execute();

        if (!empty($resultShort)) {
            $fileUrl = $resultShort['ObjectURL'];
            $this->short_sig_link = $fileUrl;

            fclose($tempShort);
        }
        if (!empty($resultLong)) {
            $fileUrl = $resultLong['ObjectURL'];
            $this->long_sig_link = $fileUrl;

            fclose($tempLong);
        }

        if ($this->save()) {
            return true;
        }

        return false;
    }

    public function getFormattedCard()
    {
        $userKey = UserKey::findOne(['public_address' => $this->public_address, 'is_revoked' => 0]);
        $user = User::findOne($userKey->user_id);

        return [
            'id' => $this->id,
            'public_address' => $this->public_address,
            'short_format_url' => $this->short_sig_link,
            'long_format_url' => $this->long_sig_link,
            'created' => Yii::$app->formatter->asDate($this->created_at),
            'user' => $user->getShortFormattedData()
        ];
    }


    public function upload($signatureArray, Document $doc)
    {
        $address = isset($signatureArray["address"]) ? $signatureArray["address"] : "";
        $sigMessageHash = isset($signatureArray["messageHash"]) ? $signatureArray["messageHash"] : null;
        $sig = isset($signatureArray["signature"]) ? $signatureArray["signature"] : null;
        $created = isset($signatureArray["signatureCreateTimestamp"]) ? strtotime($signatureArray["signatureCreateTimestamp"]) : null;

        $awsPath = Yii::$app->getUser()->id . '/documents/' . $doc->id . '/signatures/' . $sigMessageHash . '_' . $address;

        $this->public_address = $address;
        $this->document_id = $doc->id;
        $this->hash = $sigMessageHash;
        $this->message = $doc->content.$doc->nonce;
        $this->sig = $sig;
        $this->created_at = strtotime($created);

        if ($this->save()) {
            $this->saveFileSignature($awsPath);
        }
    }

    public function verifySignature()
    {
        $hexMessage = $this->hexMessage($this->message);
        $hashMessage = $this->hashMessage($hexMessage);
        if ($hashMessage !== $this->hash) {
            return false;
        }
        if (!$this->verifySig($hexMessage, $this->sig, [$this->public_address])) {
            return false;
        }

        return true;
    }


    public static function isCanSign($address, $hash)
    {
        $signatureModel = self::findOne([
            'public_address' => $address,
            'hash' => $hash,
            'is_voided' => 0,
            'is_revoked' => 0
        ]);

        if (!empty($signatureModel)) {
            return false;
        }

        return true;
    }

    public function getDateTime()
    {
        return \Yii::$app->formatter->asDate($this->created_at, 'dd MMM yyyy HH:mm:ss');
    }
}