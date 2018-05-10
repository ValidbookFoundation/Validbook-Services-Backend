<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;


class NonceMessageTemplate
{
    public $nonce;
    public $timestamp;
    public $title;
    public $message;


    public function __construct($timestamp, $title, $message)
    {
        $this->timestamp = $timestamp;
        $this->nonce = \Yii::$app->security->generateRandomString();
        $this->title = $title;
        $this->message = $message;
    }

    public function getDoc()
    {
        $template = "<?--- (((((START VALIDBOOK DOCUMENT))))) ---?>\n\n<?--- (((((START TEXT))))) ---?>\n\n";
        $template .= $this->message . "\n\n<?--- (((((END TEXT))))) ---?>\n\n<?--- (((((START PROPERTIES))))) ---?>  \n\n";
        $template .= "<?--- \n";

        $version = getenv("VALIDBOOK_DOC_VERSION");
        $properties['version'] = $version;
        $properties['DocumentTitle'];

        $template .= "<?--- \"Document-Title\": \"" . $this->title . "\" ---?>\n\n" . "<?--- \"nonce\": \"" . $this->nonce . "\" ---?>\n\n" . "<?--- \"time-nonce-generated\": \"" . $this->timestamp;

        $template .= "\n---?> \n";

        $template .= "\n<?--- (((((END PROPERTIES))))) ---?>\n";

        $template .= "\n<?--- (((((END VALIDBOOK DOCUMENT))))) ---?>  \n";

        return $template;
    }

}