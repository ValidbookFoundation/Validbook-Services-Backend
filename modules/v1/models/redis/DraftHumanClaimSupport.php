<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;

use app\modules\v1\models\card\DimSupportCard;
use yii\redis\ActiveRecord;

/**
 * Class DraftHumanCard
 * @package app\modules\v1\models\redis
 *
 */
class DraftHumanClaimSupport extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'card_address', 'sig_address', 'support_id', 'message', 'hash'];
    }


    public function getMessageForHumanClaimSupportSig()
    {
        $message['supportType'] = $this->getType();
        $message['givenToClaimHash'] = $this->hash;
        $message['supportsCard'] = $this->card_address;
        $message['supportFromCard'] = $this->sig_address;
        $message['msgDescriptiveText'] = "I support claim of {$this->card_address} card, that it uniquely represents a human individual known to me as {} on Validbook.";
        $message['msgCreateTimestamp'] = \Yii::$app->formatter->asDate(time(), 'dd MMM yyyy HH:mm:ss');

        $this->message = json_encode($message);

        return $this->message;
    }

    public function getType()
    {
        return DimSupportCard::findOne($this->support_id)->type;
    }

}