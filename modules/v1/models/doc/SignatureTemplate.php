<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;


class SignatureTemplate
{
    public $signature;
    public $address;
    public $message;
    public $messageHash;
    public $timestamp;


    public function __construct($address, $message, $messageHash, $signature, $timestamp)
    {
        $this->address = $address;
        $this->message = $message;
        $this->messageHash = $messageHash;
        $this->signature = $signature;
        $this->timestamp = $timestamp;

    }

    public function getDoc()
    {
        $template = "<?--- START SIGNATURE ---?>\n" . "<?--- {\n" .
            "\"address\": \"" . $this->address . "\",\n\"message\": \"\n" . $this->message . "\",\n\"signature\": \"" . $this->signature . "\",\n\"signature-create-timestamp\": \"" . $this->timestamp . "\"\n} ---?>\n<?--- END SIGNATURE ---?>";

        return $template;
    }

    public function getShortDoc()
    {
        $template = "<?--- START SIGNATURE ---?>\n<?--- {\n" .
            "\"address\": \"" . $this->address . "\",\n\"message-hash\": \"" . $this->messageHash . "\",\n\"signature\": \"" . $this->signature . "\",\n\"signature-create-timestamp\": \"" . $this->timestamp . "\"\n} ---?>\n<?--- END SIGNATURE ---?>";

        return $template;
    }
}