<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;

use app\modules\v1\models\card\DimClaimCard;
use yii\redis\ActiveRecord;

/**
 * Class DraftHumanCard
 * @package app\modules\v1\models\redis
 *
 */
class DraftHumanClaim extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'card_address', 'claim_id', 'message'];
    }


    public function getMessageForHumanClaimSig()
    {
        $message['claimType'] = $this->getType();
        $message['claimerCard'] = $this->card_address;
        $message['msgDescriptiveText'] = "This card {$this->card_address}, uniquely represents on Validbook platform a human individual, known to it's community by the name . I,  do not have other Validbook account cards that represent me as a human individual. This Validbook card is controlled by me, only.";
        $message['msgCreateTimestamp'] = \Yii::$app->formatter->asDate(time(), 'dd MMM yyyy HH:mm:ss');

        $this->message = json_encode($message);

        return $this->message;
    }

    public function getType()
    {
        return DimClaimCard::findOne($this->claim_id)->type;
    }

}