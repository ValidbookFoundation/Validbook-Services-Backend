<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\helpers\FileContentHelper;
use app\modules\v1\models\card\Card;
use Yii;
use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\Queue;

class AddFullCard extends BaseObject implements Job
{
    public $public_address;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        /** @var Card $card */
        $card = Card::findOne(['public_address' => $this->public_address]);

        $user = $card->getOwnerOfCard();

        $base = FileContentHelper::getContent($card->url);

        $newFullDoc = "<?--- (((((START VALIDBOOK DOCUMENT))))) ---?>  \n<?--- (((((START TEXT))))) ---?>\n";

        $newFullDoc .= $base;

        $newFullDoc .= "\n<?--- (((((END TEXT))))) ---?>\n";

        //Properties Section
        $newFullDoc .= "\n<?--- (((((START PROPERTIES))))) ---?>\n";
        $newFullDoc .= "\n<?--- \n";

        $version = getenv("VALIDBOOK_DOC_VERSION");
        $properties['version'] = $version;

        //card names
        //$properties['accountName'] = $card->getNamesTemplate();

        //card claims
        $cardClaims = $card->getClaimTemplate();
        if (!empty($cardClaims)) {
            $properties['cardClaims'] = $cardClaims;
        }

        //card acknowledgments
        $cardAcknowledgments = $card->getAcknowledgmentsTemplate();
        if (!empty($cardAcknowledgments)) {
            $properties["acknowledgments"] = $card->getAcknowledgmentsTemplate();
        }

        //linked digitalProperties
        $linkedDigitalProperties = $card->getLPropertiesTemplate();
        if (!empty($linkedDigitalProperties)) {
            $properties['linkedDigitalProperties'] = $card->getLPropertiesTemplate();
        }

        $newFullDoc .= json_encode($properties, JSON_PRETTY_PRINT);

        $newFullDoc .= "\n---?> \n";

        $newFullDoc .= "\n<?--- (((((END PROPERTIES))))) ---?>\n";

        $newFullDoc .= "\n<?--- (((((END VALIDBOOK DOCUMENT))))) ---?>  \n";

        $temp = tmpfile();

        fwrite($temp, $newFullDoc);

        //if userId is null (for book cover, story images)
        $awsPath = $user->id . '/card/' . 'full_card_' . $card->public_address . '.md';

        $s3->commands()->upload($awsPath, $temp)->execute();
        fclose($temp);
    }
}