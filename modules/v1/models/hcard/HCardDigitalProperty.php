<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\hcard;

use app\modules\v1\jobs\AddFullCard;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "h_card_digital_property".
 *
 * @property integer $id
 * @property integer $property
 * @property integer $card_address
 * @property string $url_property
 * @property integer $random_number
 * @property string $url_proof
 * @property integer $created_at
 * @property integer $is_valid
 */
class HCardDigitalProperty extends ActiveRecord
{

    const TYPE_VALIDBOOK = 0;
    const TYPE_FACEBOOK = 1;
    const TYPE_TWITTER = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_card_digital_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property', 'card_address', 'url_property'], 'required'],
            [['property', 'created_at', 'is_valid', 'random_number'], 'integer'],
            [['url_property'], 'unique', 'targetAttribute' => ['url_property', 'card_address']],
            [['url_property', 'url_proof',  'card_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public function getFormattedCard()
    {
        return [
            'id' => $this->id,
            'property' => $this->getPropertyName(),
            'url_property' => $this->url_property,
            'url_proof' => $this->url_proof,
            'random_number' => $this->random_number,
            'created' => $this->getDateProof(),
            'is_valid' => $this->is_valid
        ];
    }

    public function getPropertyName()
    {
        switch ($this->property) {
            case self::TYPE_VALIDBOOK:
                return 'validbook';
            case self::TYPE_FACEBOOK:
                return 'facebook';
            case self::TYPE_TWITTER:
                return 'twitter';
        }
    }

    public function getPropertyTypeName()
    {
        switch ($this->property) {
            case self::TYPE_VALIDBOOK:
                return 'Cooperation Platform';
            case self::TYPE_FACEBOOK:
                return 'Social Network';
            case self::TYPE_TWITTER:
                return 'Social Network';
        }
    }

    public static function getPropertyType($property)
    {
        switch ($property) {
            case 'validbook':
                return self::TYPE_VALIDBOOK;
            case 'facebook':
                return self::TYPE_FACEBOOK;
            case 'twitter':
                return self::TYPE_TWITTER;
        }
    }

    public function addLink($property, $propertyLink, $cardAddress)
    {
        $this->property = self::getPropertyType($property);
        $this->url_property = $propertyLink;
        $this->card_address = $cardAddress;
        $this->random_number = random_int(000000000000, 999999999999);

        if ($this->save()) {
            return true;
        }

        return false;
    }

    public function validateTweetProof()
    {
        $screenName = $this->getScreenName();
        $countOfTweets = 5;

        if ($screenName === null) {
            return false;
        }

        /**
         * @var \naffiq\twitterapi\TwitterAPI $twitter
         */
        $twitter = \Yii::$app->twitter;

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $requestMethod = 'GET';
        $getFields = '?screen_name=' . $screenName . '&count=' . $countOfTweets;

        $res = $twitter->setGetfield($getFields)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
        $tweetArray = json_decode($res, true);

        if (empty($tweetArray)) {
            return false;
        }

        $texts = ArrayHelper::getColumn($tweetArray, 'text');

        foreach ($texts as $key => $text) {
            if (preg_match("/" . $this->random_number . "/i", $text)) {
                $this->url_proof = $this->url_property . '/status/' . $tweetArray[$key]['id'];
                $this->is_valid = 1;
            }

            if ($this->url_proof !== null) {
                if ($this->update()) {
                    // job for added full card document
                    \Yii::$app->queue->push(new AddFullCard([
                        'public_address' => $this->card_address
                    ]));

                    return true;

                }
                return false;
            }
        }
    }

    public function validateFacebookProof($token)
    {
        $file = file_get_contents("https://graph.facebook.com/me/feed?fields=link&access_token={$token}");

        //@toDo check implementation with facebook

        $messageArray = json_decode($file, true);

        if (!empty($messageArray["data"])) {
            foreach ($messageArray["data"] as $mess) {
                if (isset($mess["link"])) {
                    if (preg_match("/" . $this->random_number . "/i", $mess["link"])) {
                        $this->url_proof = "https://www.facebook.com/{$mess["id"]}";
                        $this->is_valid = 1;
                    }
                }
            }
        }

        if ($this->url_proof !== null) {
            if ($this->update()) {
                // job for added full card document
                \Yii::$app->queue->push(new AddFullCard([
                    'public_address' => $this->card_address
                ]));
                return true;
            }
            return false;
        }
    }

    private function getScreenName()
    {
        $inputArray = explode("/", $this->url_property);

        if (!empty($inputArray) and count($inputArray) == 4) {
            return $inputArray[3];
        }
        return null;
    }

    public function getDateProof()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }
}
