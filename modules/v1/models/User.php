<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\models\book\Book;
use app\modules\v1\models\book\BookCustomPermissions;
use app\modules\v1\models\box\Box;
use app\modules\v1\models\following\Follow;
use app\modules\v1\models\identity\Identity;
use app\modules\v1\models\identity\IdentityKeysHistory;
use app\modules\v1\models\notification\NotificationSettings;
use app\modules\v1\models\search\Search;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\traits\AvatarTrait;
use app\modules\v1\traits\CoverTrait;
use app\modules\v1\traits\GethClientTrait;
use app\modules\v1\traits\PaginationTrait;
use app\modules\v1\traits\UserTrait;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $status
 * @property string $email
 * @property string $slug
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $hash
 * @property string $logged_in_ip
 * @property string $access_token
 * @property string $logged_in_at
 * @property string $created_ip
 * @property string $created_at
 * @property string $updated_at
 * @property integer $stories_count
 *
 * @property Profile $profile
 * @property Role $role
 */
class User extends ActiveRecord implements IdentityInterface, Search
{
    private $_fullName;
    private $_defaultChannelId;
    private $_defaultBoxId;

    const STATUS_ACTIVE = 1;
    const SCENARIO_PROFILE = 'profile';
    const SCENARIO_REGISTER = 'register';

    use AvatarTrait;
    use PaginationTrait;
    use CoverTrait;
    use UserTrait;
    use GethClientTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function validateSig($signature, $address)
    {
        return $this->verifySig($this->hexMessage((string)$this->identity->random_number), $signature, $address);
    }

    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email, first_name, last_name and password are all required in "register" scenario
            [['first_name', 'last_name'], 'required', 'on' => self::SCENARIO_REGISTER],

            // first_name and last_name are required in "profile" scenario
            [['first_name', 'last_name'], 'required', 'on' => self::SCENARIO_PROFILE],

            [['role_id', 'status', 'stories_count'], 'integer'],
            [['logged_in_at', 'created_at', 'updated_at'], 'safe'],
            [[
                'first_name',
                'last_name',
                'password',
                'hash',
                'logged_in_ip',
                'access_token',
                'created_ip',
                'slug'
            ], 'string', 'max' => 255],
            'password' => ['password', 'string', 'max' => 72, 'min' => 8],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_PROFILE => ['first_name', 'last_name'],
            self::SCENARIO_REGISTER => ['first_name', 'last_name']
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            'slug' => [
                'class' => Slug::className(),
                'slugAttribute' => 'slug',
                'attribute' => 'fullName',
                // optional params
                'ensureUnique' => true,
                'replacement' => '.',
                'lowercase' => true,
                'immutable' => false,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['slug', 'updated_at']);
                    $model->andWhere(['status' => 1]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to($model->url, true),
                        'lastmod' => $model->updated_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    public function getFullName()
    {
        $this->_fullName = $this->first_name . " " . $this->last_name;

        return $this->_fullName;
    }

    public function getDefaultChannelId()
    {
        $this->_defaultChannelId = Channel::find()->where(['is_default' => 1, 'user_id' => $this->id])->one()->id;

        return $this->_defaultChannelId;
    }

    public function getDefaultBoxId()
    {
        $this->_defaultBoxId = Box::find()->where([
            'is_default' => 1,
            'user_id' => $this->id,
            'name' => Box::DEFAULT_BOX_NAME
        ])->one()->id;

        return $this->_defaultBoxId;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStories()
    {
        return $this->hasMany(Story::className(), ['user_id' => 'id']);
    }

    /**
     * Validate password
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function refreshToken()
    {
        return $this->updateAttributes([
            'access_token' => Yii::$app->security->generateRandomString()
        ]);
    }

    public function getCover()
    {
        $cover = Cover::findOne(['model_id' => $this->id, 'is_actual' => true, 'type' => Cover::USER_TYPE]);
        if (!empty($cover)) {
            $result = [
                'picture_original' => $cover->getUrl(),
                'picture_small' => null,
                'color' => null
            ];
        } else {
            if ($this->profile->cover !== null) {
                $result = [
                    'picture_original' => null,
                    'picture_small' => null,
                    'color' => $this->profile->cover
                ];
            } else {
                $result = [
                    'picture_original' => null,
                    'picture_small' => null,
                    'color' => Yii::$app->params['defaultUserCoverColor']
                ];
            }
        }

        return $result;

    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return null;
    }

    public function getFormattedData($authUserSlug = null)
    {
        $isFollow = false;

        if ($authUserSlug !== null) {
            $authUser = User::findOne(['slug' => $authUserSlug, 'status' => User::STATUS_ACTIVE]);
            $isFollow = Follow::isFollowing($authUser, $this->id);
        }

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'slug' => $this->slug,
            'avatar230' => $this->getAvatar('230x230', $this->id),
            'avatar48' => $this->getAvatar('48x48', $this->id),
            'avatar32' => $this->getAvatar('32x32', $this->id),
            'is_follow' => $isFollow,
            'cover' => $this->getCover(),
            'identity' => [
                'name' => $this->identity->identity,
                'public_address' => $this->identity->public_address
            ]
        ];
    }

    public function getShortFormattedData()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'slug' => $this->slug,
            'cover' => $this->cover,
            'avatar230' => $this->getAvatar('230x230', $this->id),
            'avatar48' => $this->getAvatar('48x48', $this->id),
            'avatar32' => $this->getAvatar('32x32', $this->id),
        ];
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

//    public static function findByEmail($email)
//    {
//        return static::findOne(['email' => $email]);
//    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            // hash new password if set
            if ($this->password) {
                $this->setPassword($this->password);
            }
        }


        return parent::beforeSave($insert);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password, 12);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $model = new NotificationSettings();

            $serializedData = serialize(NotificationSettings::getDefault());

            $model->user_id = $this->id;
            $model->settings = $serializedData;
            $model->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Set attributes for registration
     * @param int $roleId
     * @return static
     */
    public function setRegisterAttributes($roleId)
    {
        // set default attributes
        $attributes = [
            "role_id" => $roleId,
            "created_ip" => Yii::$app->request->userIP,
            "access_token" => Yii::$app->security->generateRandomString(),
            "status" => static::STATUS_ACTIVE,
        ];

        // set attributes and return
        $this->setAttributes($attributes, false);


        return $this;
    }

    public function createDefaultChannelAndBookAndBox()
    {
        //create default book
        $bookModel = new Book();
        $bookModel->createDefault($this->id);

        //create default channel
        $channelModel = new Channel();
        $channelModel->name = $channelModel->defaultChannelName;
        $channelModel->user_id = $this->id;
        $channelModel->is_default = 1;
        $channelModel->save();

        $followModel = new Follow();
        $followModel->channel_id = $channelModel->id;
        $followModel->user_id = $this->id;
        $followModel->followee_id = $this->id;
        $followModel->is_follow = 1;
        $followModel->is_block = 0;
        $followModel->save();

        //create default box
        $boxModel = new Box();
        $boxModel->createDefault($this->id);

        //create defalut inbox for signed docs
        $boxModelSigned = new Box();
        $boxModelSigned->createDefaultSigned($this->id);
    }

    public function getMyStories()
    {
        $userId = $this->id;
        $itemsPerPage = Yii::$app->params['itemsPerPage'];

        $this->setPagination($itemsPerPage, $this->getPage());


        $storyModels = Story::find()->alias('s')
            ->innerJoin('story_book sb', 's.id=sb.story_id')
            ->where(['s.user_id' => $userId, 's.in_storyline' => 1, 'sb.is_moved_to_bin' => 0])
            ->orderBy('s.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return Story::format($storyModels);
    }

    public function getUserStories($userId)
    {
        $storyIds = $this->getAllowedStoriesIds($userId);
        $itemsPerPage = Yii::$app->params['itemsPerPage'];
        $storyModels = [];

        $this->setPagination($itemsPerPage, $this->getPage());

        if (!empty($storyIds)) {

            $storyModels = Story::find()
                ->where(['id' => $storyIds])
                ->orderBy('created_at DESC')
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->all();
        }

        return Story::format($storyModels);
    }

    private function getAllowedStoriesIds($userId)
    {
        $stories = (new Query())
            ->from('story s')
            ->innerJoin('story_book sb', 's.id = sb.story_id')
            ->innerJoin('story_permission_settings sp', 'sp.story_id = s.id')
            ->where(['s.user_id' => $userId, 's.in_storyline' => 1, 'sb.is_moved_to_bin' => 0, 'sp.permission_state' => [StoryPermissionSettings::PRIVACY_TYPE_PUBLIC, StoryPermissionSettings::PRIVACY_TYPE_CUSTOM]])
            ->orderBy('s.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();


        foreach ($stories as $key => $story) {
            if ($story['permission_state'] == StoryPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                $customStories = BookCustomPermissions::findAll(['custom_id' => $story['custom_permission_id']]);
                $users = ArrayHelper::getColumn($customStories, 'user_id');
                if (!in_array($userId, $users)) {
                    unset($stories[$key]);
                }
            }
        }

        $ids = ArrayHelper::getColumn($stories, 'story_id');

        return $ids;
    }

    public function followingList()
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $followings = ArrayHelper::getColumn((new Query())
            ->select('f.followee_id')
            ->from('follow f')
            ->innerJoin('user u', 'f.followee_id = u.id')
            ->where(['f.user_id' => $this->id, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.followee_id', $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('f.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all(), 'followee_id');

        $result = array_unique($followings);

        return self::formatUserCard($result, $this->id);
    }

    public function followersList()
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $followers = ArrayHelper::getColumn((new Query())
            ->select('f.user_id')
            ->from('follow f')
            ->innerJoin('user u', 'f.user_id = u.id')
            ->where(['f.followee_id' => $this->id, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.user_id', $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('f.created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all(), 'user_id');

        $result = array_unique($followers);

        return self::formatUserCard($result, $this->id);
    }

    public static function formatUserCard($users, $userId, $params = [])
    {
        $data = [];

        foreach ($users as $user) {

            /** @var self $modelUser */
            $modelUser = self::findOne($user);

            $avatar = isset($params['avatar'])
                ? $modelUser->getAvatar($params["avatar"], $modelUser->id)
                : $modelUser->getAvatar("100x100", $modelUser->id);

            if (!empty($modelUser)) {
                $data[] = [
                    "id" => $modelUser->getId(),
                    "first_name" => $modelUser->first_name,
                    "last_name" => $modelUser->last_name,
                    "full_name" => $modelUser->getFullName(),
                    "slug" => $modelUser->slug,
                    "avatar" => $avatar,
                    "is_follow" => Follow::isFollowing($userId, $modelUser->id),
                    "cover" => $modelUser->getCover()
                ];
            }

        }

        return $data;
    }


    public function whoToFollow($ids)
    {
        return self::formatUserCard($ids, Yii::$app->user->id, $params = ['avatar' => '48x48']);
    }

    public function getUrl()
    {
        return Yii::$app->params['siteUrl'] . "/" . $this->slug;
    }

    public function getSearchResult($q)
    {

        $result = [];

        $data = self::find()->alias('u')
            ->select("u.id, u.first_name, u.last_name, u.slug, p.avatar")
            ->leftJoin('profile p', 'p.user_id = u.id')
            ->where('u.created_at > unix_timestamp(NOW() - INTERVAL 1 HOUR)')
            ->andWhere("`u`.`first_name` LIKE '$q%' OR
       `u`.`last_name` LIKE '$q%' OR `u`.`slug` LIKE '$q%'")
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('u.id, u.first_name, u.last_name, u.slug, p.avatar')
            ->limit(50)
            ->all();

        /** @var User $model */
        foreach ($data as $model) {

            $result[$model->id] = [
                'id' => $model->id,
                'first_name' => $model->first_name,
                'last_name' => $model->last_name,
                'slug' => $model->slug,
                "public_address" => $model->identity->public_address,
                'avatar' => $model->getAvatar('32x32', $model->id),
                'relation' => null
            ];
        }
        if (!empty($this->getItemsPerPage())) {
            $this->setPagination($this->getItemsPerPage(), $this->getPage());
            $result = array_slice($result, $this->getOffset(), $this->getLimit());
        }

        return $result;

    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function sendEmail($type, $email)
    {
        $view = 'recover-pass';
        $title = 'no title';

        if ($type == 'recover') {
            $view = 'recover-pass';
            $title = 'recover password';
        }
        $mailer = \Yii::$app->mailer
            ->compose($view, ['model' => $this])
            ->setFrom(["support@validbook.org" => "Validbook"])
            ->setSubject($title)
            ->setTo($email)
            ->send();

        if ($mailer) {
            return true;
        }
    }

    public function countFollowing()
    {
        $countFollowings = (new Query())
            ->select('f.followee_id')
            ->distinct()
            ->from('follow f')
            ->innerJoin('user u', 'f.followee_id = u.id')
            ->where(['f.user_id' => $this->id, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.followee_id', $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('f.created_at DESC')
            ->count();

        return $countFollowings;
    }

    public function countFollowers()
    {
        $countFollowers = (new Query())
            ->select('f.user_id')
            ->distinct()
            ->from('follow f')
            ->innerJoin('user u', 'f.user_id = u.id')
            ->where(['f.followee_id' => $this->id, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.user_id', $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('f.created_at DESC')
            ->count();

        return $countFollowers;
    }

    public function createIdentity($publicAddress, $recoveryAddress)
    {
        $identity = new Identity();
        $identity->public_address = $publicAddress;
        $identity->recovery_address = $recoveryAddress;
        $identity->identity = $this->slug;
        $identity->fullName = $this->profile->full_name;
        $template = $identity->template;
        $hashMessage = $this->hashMessage($template);
        $identity->hash = $hashMessage;

        $fileName = 'identity_' . $this->slug;
        $temp = tmpfile();
        fwrite($temp, $template);
        $s3 = Yii::$app->get('s3');
        $awsPath = 'identity/' . $fileName . '.json';
        $result = $s3->commands()->upload($awsPath, $temp)->execute();
        if (!empty($result)) {
            $fileUrl = $result['ObjectURL'];
            $identity->url = $fileUrl;
            fclose($temp);
        }

        if($identity->save()) {
            $userKeys = new IdentityKeysHistory();
            $userKeys->identity_id = $identity->id;
            $userKeys->recovery_address = $recoveryAddress;
            $userKeys->public_address = $publicAddress;
            $userKeys->is_revoked = 0;
            $userKeys->save();
        }
    }

    public function getIdentity()
    {
        return $this->hasOne(Identity::className(), ['identity' => 'slug']);
    }
}
