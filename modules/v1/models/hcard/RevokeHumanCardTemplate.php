<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\hcard;


class RevokeHumanCardTemplate
{
    public $address;
    public $timestamp;
    public $user_id;

    public function getMessage()
    {
        $template = "<?--- START REVOCATION MESSAGE ---?>\n\n" .
            "\"payload\": \"" . "Revoke Human Card" . "\"\n\n\"address\": \"" . $this->address . "\"\n\n\"timestamp\": \"" . $this->timestamp . "\"\n\n<?--- END REVOCATION MESSAGE ---?>";

        return $template;
    }
}