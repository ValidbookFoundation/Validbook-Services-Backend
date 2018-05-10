<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\hcard;

/**
 * <?--- START LINKED DIGITAL PROPERTY ---?>
 * <?--- "property-name": "validbook" ---?>
 * <?--- "URL-to-property": "http://validbook.org/john.smith" ---?>
 * <?--- "comment": "This validbook account was linked to this human card at the creation of the human card." ---?>
 * <?--- "date-of-linkage": "20-Sep-2017" ---?>
 * <?--- END LINKED DIGITAL PROPERTY ---?>
 */

/**
 * Class ValidbookDPTemplate
 * @package app\modules\v1\models\hcard
 */
class ValidbookDPTemplate extends DPTemplate
{
    public $comment = "This validbook account was linked to this human card at the creation of the human card.";

    public function getDoc()
    {
        $template = "<?--- START LINKED DIGITAL PROPERTY ---?>\n";
        $template .= "<?--- \"property-name\": \"" . $this->property_name . "\" ---?>\n";
        $template .= "<?--- \"URL-to-property\": \"" . $this->url_to_property . "\" ---?>\n";
        $template .= "<?--- \"comment\": \"" . $this->comment . "\" ---?>\n";
        $template .= "<?--- \"date-of-linkage\": \"" . $this->date_of_proof . "\" ---?>\n";
        $template .= "<?--- END LINKED DIGITAL PROPERTY ---?>\n";

        return $template;
    }
}