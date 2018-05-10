<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\models\story\Story;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $entity
 * @property integer $entity_id
 * @property string $content
 * @property integer $parent_id
 * @property integer $level
 * @property integer $created_by
 * @property string $related_to
 * @property string $url
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property User $createdBy
 * @property User $updatedBy
 */
class Comment extends ActiveRecord
{
    const DEFAULT_ENTITY = 'story';

    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'entity_id', 'content', 'created_by'], 'required'],
            [['entity_id', 'parent_id', 'level', 'created_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content', 'url'], 'string'],
            [['entity'], 'string', 'max' => 20],
            [['related_to'], 'string', 'max' => 500],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity' => 'Entity',
            'entity_id' => 'Entity ID',
            'content' => 'Content',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'created_by' => 'Created By',
            'related_to' => 'Related To',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
            ]
        ];
    }


    public function getFormattedData()
    {
        return [
            'id' => $this->id,
            'entity' => $this->entity,
            'entity_id' => $this->entity_id,
            'date' => $this->getDate(),
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'parent' => self::getParent($this->parent_id),
            'children' => $this->getChildrenForComment(),
            'user' => $this->getCreatedBy()
        ];
    }

    public function getDate()
    {
        if ($this->update()) {
            return Yii::$app->formatter->asDate($this->updated_at);
        }
        return Yii::$app->formatter->asDate($this->created_at);
    }

    public static function isReply($parentId)
    {
        if ($parentId !== 0) {
            $model = self::find()->where(['parent_id' => $parentId])->one();
            if (!empty($model)) {
                return true;
            }
        }

        return false;
    }

    public static function getParentAuthor($parentId)
    {
        /** @var Comment $model */
        $model = self::find()->select('created_by')->where(['parent_id' => $parentId])->one();
        if (!empty($model)) {
            return $model->created_by;
        }
        return null;
    }

    private static function getParent($parentId)
    {
        $model = self::findOne($parentId);
        if ($model != null) {
            return $model->getFormattedData();
        }
        return [];
    }

    /**
     * @return array
     */
    public function getCreatedBy()
    {
        return User::findOne($this->created_by)->getShortFormattedData();
    }

    public function getCommentsForStory($entityId, $page = 1, $from)
    {
        if ($from === 'storyline') {
            $this->setPagination(3, 1);
        } elseif ($from === 'story') {
            $this->setPagination(11, $page);
        }

        $result = [];

        $modelsList = Comment::find()
            ->innerJoin('user u', 'created_by = u.id')
            ->where(['entity_id' => $entityId, 'parent_id' => 0])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $modelsList = array_reverse($modelsList);


        if (!empty($modelsList)) {
            /** @var Comment $model */
            foreach ($modelsList as $model) {
                if ($from === 'storyline' || $from === 'story') {
                    $children = $model->getLastReply();
                } else {
                    $children = $this->getChildrenForComment();
                }

                $result[] = ['id' => $model->id,
                    'entity' => $model->entity,
                    'entity_id' => $model->entity_id,
                    'date' => $model->getDate(),
                    'parent_id' => $model->parent_id,
                    'parent' => self::getParent($model->parent_id),
                    'content' => $model->content,
                    'children' => $children,
                    'user' => $model->getCreatedBy(),
                ];
            }
        }
        return $result;
    }


    private function getChildrenForComment()
    {
        $result = [];
        $modelList = self::find()
            ->innerJoin('user u', 'created_by = u.id')
            ->where(['parent_id' => $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->all();

        $counts = ["reply" => count($modelList)];

        if (!empty($modelList)) {
            /** @var Comment $model */
            foreach ($modelList as $model) {
                $result[] = [
                    'id' => $model->id,
                    'entity' => $model->entity,
                    'entity_id' => $model->entity_id,
                    'date' => $model->getDate(),
                    'content' => $model->content,
                    'children' => $model->getChildrenForComment(),
                    'user' => $model->getCreatedBy(),
                    "counts" => $counts
                ];
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStory()
    {
        return $this->hasOne(Story::className(), ['id' => 'entity_id']);
    }

    public function getDataForNewComment()
    {
        $result = [
            'id' => $this->id,
            'entity' => $this->entity,
            'entity_id' => $this->entity_id,
            'date' => $this->getDate(),
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'children' => $this->getChildrenForComment(),
            'user' => $this->getCreatedBy()];

        return $result;
    }

    public static function allCommentatorsOfStory($storyId, $userId)
    {
        $allCommentatorsStory = ArrayHelper::getColumn(
            self::find()
                ->select('created_by')
                ->distinct()
                ->innerJoin('user u', 'created_by = u.id')
                ->where(['entity_id' => $storyId, 'entity' => 'story'])
                ->andWhere(['<>', 'created_by', $userId])
                ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->all(),
            'created_by');

        return $allCommentatorsStory;
    }

    private function getLastReply()
    {
        $result = [];
        /** @var self $model */
        $model = self::find()
            ->innerJoin('user u', 'created_by = u.id')
            ->where(['parent_id' => $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->one();

        $modelReplyCount = (int)self::find()
            ->innerJoin('user u', 'created_by = u.id')
            ->where(['parent_id' => $this->id])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('created_at DESC')
            ->count();

        $counts = ['reply' => $modelReplyCount];

        if (!empty($model)) {

            $result[] = ['id' => $model->id,
                'entity' => $model->entity,
                'entity_id' => $model->entity_id,
                'date' => $model->getDate(),
                'content' => $model->content,
                'children' => [],
                'counts' => $counts,
                'user' => $model->getCreatedBy()
            ];
        };
        return $result;
    }
}
