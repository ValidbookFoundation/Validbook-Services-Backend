<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\hcard;

/**
 * <?--- START LINKED DIGITAL PROPERTY ---?>
 * <?--- "property-name": "facebook" ---?>
 * <?--- "URL-to-property": "http://facebook.com/john.smith" ---?>
 * <?--- "random-number-for-posting-by-or-on-property": "Wwc4H4PdyMS-jwGOvyHHpLFU-vE_U9USgf04rb-FQj8" ---?>
 * <?--- "URL-to-proof": "https://www.facebook.com/bohdan.andriyiv/posts/10211535569519042" ---?>
 * <?--- "date-of-proof-creation": "20-Sep-2017" ---?>
 * <?--- END LINKED DIGITAL PROPERTY ---?>
 */


/**
 * Class ValidbookDPTemplate
 * @package app\modules\v1\models\hcard
 */
class DPTemplate
{
    public $property_name;
    public $url_to_property;
    public $random_number;
    public $url_to_proof;
    public $date_of_proof;

    public function getDoc()
    {
        $template = "<?--- START LINKED DIGITAL PROPERTY ---?>\n";
        $template .= "<?--- \"property-name\": \"" . $this->property_name . "\" ---?>\n";
        $template .= "<?--- \"URL-to-property\": \"" . $this->url_to_property . "\" ---?>\n";
        $template .= "<?--- \"random-number-for-posting-by-or-on-property\": \"" . $this->random_number . "\" ---?>\n";
        $template .= "<?--- \"URL-to-proof\": \"" . $this->url_to_proof . "\" ---?>\n";
        $template .= "<?--- \"date-of-proof-creation\": \"" . $this->date_of_proof . "\" ---?>\n";
        $template .= "<?--- END LINKED DIGITAL PROPERTY ---?>\n";

        return $template;
    }
}