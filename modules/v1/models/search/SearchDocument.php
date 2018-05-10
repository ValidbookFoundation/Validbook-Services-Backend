<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\search;

use app\modules\v1\models\box\Box;
use app\modules\v1\models\doc\DimPermissionDocument;
use app\modules\v1\models\doc\Document;
use app\modules\v1\models\doc\DocumentCustomPermissions;
use app\modules\v1\models\doc\DocumentPermissionSettings;
use app\modules\v1\models\Signature;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\sphinx\ActiveRecord;
use yii\sphinx\MatchExpression;

/**
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $user_id
 * @property integer $box_id
 * @property integer $is_moved_to_bin
 * @property integer $is_signed
 * @property string $url
 * @property string $icon
 * @property string $hash
 * @property integer $created_at
 * @property integer $is_encrypted
 * @property integer is_open_for_sig
 */
class SearchDocument extends ActiveRecord implements Search
{
    use PaginationTrait;

    public static function indexName()
    {
        return 'document';
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }


    private static function mapSettings()
    {
        $models = DimPermissionDocument::find()->all();
        $map = ArrayHelper::getColumn(ArrayHelper::index($models, 'id'), 'name');
        return $map;
    }

    private function matchResult($q)
    {
        $q = \Yii::$app->sphinx->escapeMatchValue($q);

        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        $models = self::find()
            ->match((new MatchExpression())
                ->match(['title' => $q])
                ->orMatch(['content' => $q])
            )
            ->all();


        if (!empty($this->getItemsPerPage())) {
            $this->setPagination($this->getItemsPerPage(), $this->getPage());
            $models = array_slice($models, $this->getOffset(), $this->getLimit());
        }

        return $models;
    }

    public function getSearchResult($q)
    {
        $data = [];

        $documents = $this->matchResult($q);


        if (!empty($documents)) {
            /** @var SearchDocument $doc */
            foreach ($documents as $doc) {
                $box = Box::findOne($doc->box_id);
                if ($box->isExistenceVisibile() and $box->isContentVisible() and !$box->isInBin()) {
                    $data[] = [
                        'id' => $doc->id,
                        'title' => $doc->title,
                        'type' => $doc->getType(),
                        'box_id' => $doc->box_id,
                        'user_id' => $doc->user_id,
                        'icon' => $doc->getPreviewIcon(),
                        'url' => $doc->getUrl(),
                        'created' => Yii::$app->formatter->asDate($this->created_at),
                        'signatures' => $doc->getSignatures(),
                        'hash' => $doc->isContentVisible() ? $doc->hash : null,
                        'is_open_for_sign' => $doc->is_open_for_sig,
                        'is_encrypted' => $doc->is_encrypted,
                        'settings' => $doc->getSettings()
                    ];
                }
            }

        }
        return $data;
    }


    private function getType()
    {
        if ($this->type == Document::TYPE_CUSTOM) {
            return "custom";
        }
    }

    private function getUrl()
    {
        if ($this->isContentVisible()) {
            return $this->url;
        }
        return null;
    }

    private function getSignatures()
    {
        $result = [];

        $signatures = Signature::findAll(['hash' => $this->hash, 'type' => Document::TYPE_CUSTOM, 'is_voided' => 0]);

        foreach ($signatures as $signature) {
            $result[] = $signature->getFormattedCard();
        }

        return $result;
    }

    /**
     * Check if current user can see content of this document
     *
     * @return bool
     */
    public function isContentVisible()
    {
        return $this->checkVisibility('can_see_content');
    }

    private function getSettings()
    {
        $data = [];
        $docSettings = DocumentPermissionSettings::find()->where(['doc_id' => $this->id])->all();
        $docSettings = ArrayHelper::index($docSettings, 'permission_id');
        $mapSettings = self::mapSettings();
        foreach ($mapSettings as $key => $setting) {
            $data[$setting] = $docSettings[$key]->permission_state;
        }
        $data['users_array'] = [
            'users_can_see_content' => $docSettings[1]->permission_state == 2 ? DocumentCustomPermissions::getUsers($docSettings[1]->custom_permission_id) : [],
            'users_can_sign' => $docSettings[2]->permission_state == 2 ? DocumentCustomPermissions::getUsers($docSettings[2]->custom_permission_id) : []
        ];

        return $data;
    }

    private function checkVisibility($field)
    {
        $userId = isset(Yii::$app->user) ? Yii::$app->user->id : null;

        if ($userId === $this->user_id) {
            return true;
        }

        $boxSettings = DocumentPermissionSettings::find()->where(['doc_id' => $this->id])->all();
        $mapSettings = self::mapSettings();

        $key = array_search($field, $mapSettings, true);

        /** @var DocumentPermissionSettings $setting */
        foreach ($boxSettings as $setting) {
            if ($key == $setting->permission_id) {
                if ($setting->permission_state == DocumentPermissionSettings::PRIVACY_TYPE_PUBLIC) {
                    return true;
                } elseif ($setting->permission_state == DocumentPermissionSettings::PRIVACY_TYPE_PRIVATE) {
                    return false;
                } elseif ($setting->permission_state == DocumentPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                    $customs = (new Query())
                        ->from('document_custom_permissions')
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

    private function getPreviewIcon()
    {
        if ($this->isContentVisible()) {
            if ($this->is_encrypted) {
                $icon = Yii::$app->params["closedDocumentIcon"];

                return $icon;
            }
            return $this->icon;
        }
        return Yii::$app->params["closedDocumentIcon"];
    }


}