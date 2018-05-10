<?php

namespace backend\models;

use Yii;

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
 * @property integer $birthDate
 * @property integer $birthDateVisibility
 * @property integer $birthYear
 * @property integer $birthYearVisibility
 * @property string $twitter
 * @property string $facebook
 * @property string $linkedin
 * @property string $website
 * @property string $phone
 * @property string $skype
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
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
            [['user_id', 'bio', 'company', 'birthDate', 'birthDateVisibility', 'birthYear', 'birthYearVisibility'], 'required'],
            [['user_id', 'country_id', 'birthDate', 'birthDateVisibility', 'birthYear', 'birthYearVisibility'], 'integer'],
            [['bio'], 'string'],
            [['full_name', 'timezone', 'occupation', 'company', 'location', 'twitter', 'facebook', 'linkedin', 'website', 'phone', 'skype'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'full_name' => 'Full Name',
            'timezone' => 'Timezone',
            'bio' => 'Bio',
            'occupation' => 'Occupation',
            'company' => 'Company',
            'country_id' => 'Country ID',
            'location' => 'Location',
            'birthDate' => 'Birth Date',
            'birthDateVisibility' => 'Birth Date Visibility',
            'birthYear' => 'Birth Year',
            'birthYearVisibility' => 'Birth Year Visibility',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'linkedin' => 'Linkedin',
            'website' => 'Website',
            'phone' => 'Phone',
            'skype' => 'Skype',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
