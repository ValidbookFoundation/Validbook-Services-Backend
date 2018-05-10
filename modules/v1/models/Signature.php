<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\jobs\AddFullCard;
use app\modules\v1\jobs\AddSupporterHumanClaimCard;
use app\modules\v1\jobs\DeleteValidationHumanCard;
use app\modules\v1\jobs\DeleteValidationHumanCardNode;
use app\modules\v1\models\card\Card;
use app\modules\v1\models\doc\SignatureTemplate;
use app\modules\v1\traits\GethClientTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "signature".
 *
 * @property integer $id
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
class Signature extends ActiveRecord
{

    use GethClientTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'signature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'is_voided', 'is_revoked'], 'integer'],
            [['public_address', 'sig', 'message', 'short_sig_link', 'long_sig_link'], 'required'],
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

        return [
            'id' => $this->id,
            'public_address' => $this->public_address,
            'short_format_url' => $this->short_sig_link,
            'long_format_url' => $this->long_sig_link,
            'created' => Yii::$app->formatter->asDate($this->created_at),
            "is_valid" => true,
        ];
    }


    public function addRelationshipToNode(User $usr1, User $usr2, $usr1_address, $usr2_address, $usr2_created)
    {
        // added job to push models in query
        Yii::$app->queue->push(new AddSupporterHumanClaimCard([
            'usr1Id' => $usr1->getId(),
            'usr2Id' => $usr2->getId(),
            'usr1_address' => $usr1_address,
            'usr2_address' => $usr2_address,
            'usr1_created' => $this->created_at,
            'usr2_created' => $usr2_created
        ]));
    }

    public function deleteNode($usrId)
    {
        // added job to push models in query
        Yii::$app->queue->push(new DeleteValidationHumanCardNode([
            'usrId' => $usrId
        ]));
    }

    public function revoked(Card $ownerCard)
    {
        $this->is_revoked = 1;

        $card = Card::findOne(['public_address' => $this->public_address, 'is_revoked' => 0]);

        // job for added full card document
        Yii::$app->queue->push(new AddFullCard([
            'public_address' => $card->public_address
        ]));

        // job for added full card document
        Yii::$app->queue->push(new AddFullCard([
            'public_address' => $this->public_address
        ]));


        $this->deleteNodeRelationships($card->getOwnerOfCard(), $ownerCard->getOwnerOfCard());


        if ($this->update()) {
            return true;
        };
        return false;
    }

    private function deleteNodeRelationships(User $usr1, User $usr2)
    {
        // added job to push models in query
        Yii::$app->queue->push(new DeleteValidationHumanCard([
            'usr1Id' => $usr1->getId(),
            'usr2Id' => $usr2->getId()
        ]));
    }

    public static function isCanValidate($address, $hash)
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