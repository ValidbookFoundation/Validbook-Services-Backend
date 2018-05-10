<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\models\card\Card;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $full_name
 * @property string $timezone
 * @property string $bio
 * @property string $occupation
 * @property string $company
 * @property integer $country_id
 * @property string $location
 * @property integer $birthDay
 * @property integer $birthMonth
 * @property integer $birthDateVisibility
 * @property integer $birthYear
 * @property integer $birthYearVisibility
 * @property string $twitter
 * @property string $facebook
 * @property string $linkedin
 * @property string $website
 * @property string $phone
 * @property string $skype
 * @property string $avatar
 * @property string $cover
 * @property integer $calm_mode_notifications
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    public $first_name;
    public $last_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'country_id', 'birthDateVisibility', 'birthYear', 'birthYearVisibility', 'birthMonth', 'calm_mode_notifications', 'birthDay'], 'integer'],
            [['bio'], 'string'],
            [[
                'full_name',
                'timezone',
                'occupation',
                'company',
                'location',
                'twitter',
                'facebook',
                'linkedin',
                'website',
                'phone',
                'skype',
                'avatar',
                'cover',
                'first_name',
                'last_name'
            ], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);


    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Set user id
     * @param int $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    public function formatResponseData($params = [])
    {
        $data = [
            "user_id" => $this->user_id,
            "first_name" => $this->user->first_name,
            "last_name" => $this->user->last_name,
            "bio" => $this->bio,
            "occupation" => $this->occupation,
            "company" => $this->company,
            "country_id" => $this->country_id,
            "location" => $this->location,
            "birthDay" => $this->birthDay,
            "birthMonth" => $this->birthMonth,
            "birthDateVisibility" => $this->birthDateVisibility,
            "birthYear" => $this->birthYear,
            "birthYearVisibility" => $this->birthYearVisibility,
            "twitter" => $this->twitter,
            "facebook" => $this->facebook,
            "linkedin" => $this->linkedin,
            "website" => $this->website,
            "phone" => $this->phone,
            "skype" => $this->skype,
            "card" => $this->getCard(),
            "calm_mode_notifications" => $this->calm_mode_notifications
        ];
        if (!empty($params) && is_array($params)) {
            $data = array_merge($data, $params);
        }

        return $data;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getCard()
    {
        $userKey = UserKey::findOne(['user_id' => $this->user_id, 'is_revoked' => 0]);

        /** @var Card $card */
        $card = Card::find()->where(['public_address' => $userKey->public_address, 'is_revoked' => 0])->one();

        if (!empty($card)) {
            return $card->getShortFormattedCard();
        } else {
            return null;
        }
    }
}
