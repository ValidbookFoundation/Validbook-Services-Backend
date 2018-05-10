<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\box;

use app\modules\v1\models\doc\Document;
use app\modules\v1\models\TreeQuery;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "box".
 *
 * @property integer $id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property string $slug
 * @property integer $user_id
 * @property integer $is_root
 * @property integer $is_default
 * @property string $description
 * @property string $cover
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_moved_to_bin
 *
 * @property User $author
 */
class Box extends ActiveRecord
{
    private $_url;

    const DEFAULT_BOX_NAME = "Desk";
    const DEFAULT_SIGNED_BOX = "Backup of signed documents";
    const URLDELIMITER = "-";

    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'box';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['lft', 'rgt', 'depth', 'user_id', 'is_root', 'is_default', 'is_moved_to_bin'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'cover'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
                'attribute' => 'name',
                // optional params
                'ensureUnique' => true,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => false,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
        ];
    }

    //get full url for sitemap
    public function getFullurl()
    {
        if (!isset($this->author->slug)) {
            return Yii::$app->params['siteUrl'] . '/' . $this->author . "/boxes/" . $this->url;
        }
    }


    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new TreeQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function getDefaultBoxName()
    {
        return self::DEFAULT_BOX_NAME;
    }

    public function createDefault($userId)
    {
        $rootBoxId = $this->createRoot($userId);

        $parentModel = Box::findOne([
            'id' => $rootBoxId
        ]);

        $model = new Box();
        $model->name = $this->getDefaultBoxName();
        $model->user_id = $userId;
        $model->is_default = 1;

        if ($model->prependTo($parentModel)) {
            BoxPermissionSettings::setValues($model->id);
        }
        return $model;
    }

    public function createDefaultSigned($userId)
    {
        $rootBoxId = $this->createRoot($userId);

        $parentModel = Box::findOne([
            'id' => $rootBoxId
        ]);

        $model = new Box();
        $model->name = $this->getSignedDocsBoxName();
        $model->user_id = $userId;
        $model->is_default = 1;

        if ($model->prependTo($parentModel)) {
            BoxPermissionSettings::setValues($model->id);
        }
    }

    public function createRoot($userId, $name = 'root')
    {
        if (self::find()
            ->where([
                'user_id' => $userId,
                'depth' => 0,
                'lft' => 0
            ])
            ->exists()) {

            return false;
        }

        $model = new Box([
            'name' => $name,
            'user_id' => $userId,
            'is_root' => 1
        ]);

        $model->makeRoot();

        return $model->id;
    }


    public function getUrl()
    {
        $this->_url = $this->slug;

        return $this->_url;
    }


    public function getIcon()
    {
        $boxSettings = BoxPermissionSettings::find()->where(['box_id' => $this->id])->all();

        $boxSettings = ArrayHelper::getColumn(ArrayHelper::index($boxSettings, 'permission_id'), 'permission_state');


        if ($boxSettings[1] == 1 && $boxSettings[2] == 1 and $this->name !== $this->getDefaultBoxName()) {
            return "public";
        } elseif ($boxSettings[1] == 1 and $boxSettings[2] == 1 and $this->name == $this->getDefaultBoxName()) {
            return "desk";
        } elseif ($this->name == $this->getSignedDocsBoxName()) {
            return "backup";
        }

        if (($boxSettings[1] == 0) or ($boxSettings[2] == 0)) {
            return "private";

        }
        if (($boxSettings[1] == 2) or ($boxSettings[2] == 2)) {
            return "custom";
        }

    }

    public static function getRemovedBoxes($userId)
    {
        $result = [];

        $models = self::findAll(['user_id' => $userId, 'is_moved_to_bin' => 1]);
        $modelsDoc = Document::findAll(['user_id' => $userId, 'is_moved_to_bin' => 1]);

        foreach ($models as $box) {
            $result['boxes'] = [
                'id' => $box['id'],
                'name' => $box['name'],
                'key' => $box['slug'],
                'icon' => 'bin',
                'no_drag' => true
            ];
        }

        /** @var Document $doc */
        foreach ($modelsDoc as $doc) {
            $result['documents'][] = $doc->formatCard();
        }

        return $result;
    }

    public function isInBin()
    {
        return boolval($this->is_moved_to_bin);
    }

    /**
     * Check if current user can see existence of this box
     *
     * @return bool
     */
    public function isExistenceVisibile()
    {
        return $this->checkVisibility('can_see_exists');
    }

    /**
     * Check if current user can see content of this box
     *
     * @return bool
     */
    public function isContentVisible()
    {
        return $this->checkVisibility('can_see_content');
    }

    /**
     * @return bool
     */
    private function checkVisibility($field)
    {
        $userId = isset(Yii::$app->user) ? Yii::$app->user->id : null;

        if ($userId === $this->user_id) {
            return true;
        }

        $boxSettings = BoxPermissionSettings::find()->where(['box_id' => $this->id])->all();
        $mapSettings = BoxPermissionSettings::mapSettings();

        $key = array_search($field, $mapSettings, true);

        /** @var BoxPermissionSettings $setting */
        foreach ($boxSettings as $setting) {
            if ($key == $setting->permission_id) {
                if ($setting->permission_state == BoxPermissionSettings::PRIVACY_TYPE_PUBLIC) {
                    return true;
                } elseif ($setting->permission_state == BoxPermissionSettings::PRIVACY_TYPE_PRIVATE) {
                    return false;
                } elseif ($setting->permission_state == BoxPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                    $customs = (new Query())
                        ->from('box_custom_permissions')
                        ->where(['custom_id' => $setting->custom_permission_id])
                        ->all();
                    $users = ArrayHelper::getColumn($customs, 'user_id');
                    if (in_array($userId, $users)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getSettings()
    {
        $data = [];
        $boxSettings = BoxPermissionSettings::find()->where(['box_id' => $this->id])->all();
        $boxSettings = ArrayHelper::index($boxSettings, 'permission_id');
        $mapSettings = BoxPermissionSettings::mapSettings();
        foreach ($mapSettings as $key => $setting) {
            $data[$setting] = $boxSettings[$key]->permission_state;
        }
        $data['users_array'] = [
            'users_can_see_exists' => $boxSettings[1]->permission_state == 2 ? BoxCustomPermissions::getUsers($boxSettings[1]->custom_permission_id) : [],
            'users_can_see_content' => $boxSettings[2]->permission_state == 2 ? BoxCustomPermissions::getUsers($boxSettings[2]->custom_permission_id) : [],
            'users_can_add_documents' => $boxSettings[3]->permission_state == 2 ? BoxCustomPermissions::getUsers($boxSettings[3]->custom_permission_id) : [],
            'users_can_delete_documents' => $boxSettings[4]->permission_state == 2 ? BoxCustomPermissions::getUsers($boxSettings[4]->custom_permission_id) : [],
        ];

        return $data;
    }

    public function childs($node, $modelUser)
    {
        $identityId = Yii::$app->user->getId();
        if (empty($node))
            return [];

        $boxes = [];

        $children = $node->children()->all();

        foreach ($children as $key => $child) {
            if ($child->name !== self::getDefaultBoxName()) {
                if ($modelUser->getId() !== $identityId) {
                    $childPermissions = BoxPermissionSettings::findAll(['box_id' => $child->id]);
                    foreach ($childPermissions as $permission) {
                        if ($permission->permission_id == 1 and $permission->permission_state == 0) {
                            unset($children[$key]);
                        }
                        if ($permission->permission_id == 1 and $permission->permission_state == 2) {
                            $customUsersIds = ArrayHelper::getColumn(BoxCustomPermissions::findAll(['custom_id' => $permission->custom_permission_id]), 'user_id');
                            if (!in_array($identityId, $customUsersIds)) {
                                unset($children[$key]);
                            }
                        }
                    }
                }
            }

            foreach ($children as $key => $child) {
                if (!$child->isInBin()) {

                    $boxItem = [
                        'id' => $child->id,
                        'name' => $child->name,
                        'key' => $child->getUrl(),
                        'icon' => $child->getIcon(),
                        'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/boxes/' . $child->getUrl()], true),
                    ];

                    if ($child->is_default == 1) {
                        $desk = array_merge($boxItem, [
                            'no_drag' => true,
                        ]);

                        //add desk  to the beginning of the array
                        array_unshift($boxes, $desk);
                    } else {
                        $children = $this->childs($child, $modelUser);

                        $boxes[] = array_merge($boxItem, [
                            'no_drag' => false,
                            'children' => $children,
                        ]);
                    }
                }
            }
        }


        return $boxes;
    }

    public function addDesk($modelUser)
    {

        $desk = Box::findOne(['user_id' => $modelUser->getId(), 'name' => self::getDefaultBoxName()]);
        $children = $desk->childs($desk, $modelUser);

        $result = [
            'id' => $desk->id,
            'name' => $desk->name,
            'key' => $desk->getUrl(),
            'icon' => $desk->getIcon(),
            'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/boxes/' . $desk->getUrl()], true),
            'children' => $children

        ];
        return $result;
    }

    function addSigned($modelUser)
    {

        $identityId = Yii::$app->user->getId();

        $signed = Box::findOne(['user_id' => $modelUser->getId(), 'name' => self::getSignedDocsBoxName()]);

        if ($identityId !== $modelUser->getId()) {
            $result = null;
        } else {
            $result = [
                'id' => $signed->id,
                'name' => $signed->name,
                'key' => $signed->getUrl(),
                'icon' => $signed->getIcon(),
                'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/boxes/' . $signed->getUrl()], true),
            ];
        }

        return $result;
    }

    public function addBinTree($userId)
    {
        $removedBoxes = self::getRemovedBoxes($userId);

        $bin = [
            'name' => "Bin",
            'key' => "bin",
            'icon' => "bin",
            'no_drag' => true,
            'children' => $removedBoxes
        ];

        return $bin;
    }

    public function getDocuments()
    {
        $result = [];
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $documents = Document::find()->where(['box_id' => $this->id, 'is_moved_to_bin' => 0])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var Document $document */
        foreach ($documents as $document) {
            $result[] = $document->formatCard();
        }
        return $result;
    }

    public function getSignedDocuments($userId)
    {
        $user = User::findOne($userId);

        $docs = [];
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $documents = Document::find()->alias("doc")
            ->innerJoin("signature s", 'doc.hash = s.hash')
            ->where([
                'doc.is_moved_to_bin' => 0,
                'doc.is_signed' => true,
                's.public_address' => $user->userKey->public_address
            ])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var Document $doc */
        foreach ($documents as $doc) {
            $box = Box::findOne(['id' => $doc->box_id]);
            $boxes[$doc->hash][] = [
                'box_slug' => $box->slug,
                'user_id' => $doc->user_id
            ];

            $docs[$doc->hash] = [
                'title' => $doc->title,
                'type' => $doc->getType(),
                'url' => $doc->url,
                'signatures' => $doc->getSignatures(),
                'hash' => $doc->hash,
                'boxes' => $boxes[$doc->hash]
            ];
        }

        $docs = array_values($docs);
        return $docs;
    }

    public function addSignedDocs($userId)
    {

        $signed = [
            'name' => "My Signed Documents",
            'key' => "signed",
            'icon' => "signed_documents",
            'no_drag' => true,
            'signed_documents' => $this->getSignedDocuments($userId),
        ];

        return $signed;
    }

    public function getSignedDocsBoxName()
    {
        return self::DEFAULT_SIGNED_BOX;
    }

    public function canUpdate()
    {
        if (!$this->is_default) {
            return true;
        }

        return false;
    }

    public function canDelete()
    {
        if (!$this->is_default) {
            return true;
        }

        return false;
    }

//    public function getCardUrl()
//    {
//        $cardAddress = null;
//
//        $desk = Box::findOne(['user_id' => $this->user_id, 'name' => self::getDefaultBoxName()]);
//
//        /** @var Card $card */
//        $card = Card::find()->where(['box_id' => $desk->id, 'is_revoked' => 0])->one();
//
//        if (!empty($card)) {
//            $cardAddress = $card->public_address;
//        }
//
//        return $cardAddress;
//    }

}
