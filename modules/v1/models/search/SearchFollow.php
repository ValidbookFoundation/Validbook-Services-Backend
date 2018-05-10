<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\search;

use app\modules\v1\traits\AvatarTrait;
use app\modules\v1\traits\PaginationTrait;
use yii\helpers\StringHelper;
use yii\sphinx\ActiveRecord;
use yii\sphinx\MatchExpression;

/**
 * This is the model class for index "follow".
 *
 * @property integer $id
 * @property integer $me_id
 * @property integer $he_id
 * @property integer $first_name
 * @property string $last_name
 * @property string $slug
 * @property string $avatar
 * @property integer $f_date
 * @property string $relation
 */
class SearchFollow extends ActiveRecord implements Search
{

    use AvatarTrait;
    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'follow';
    }

    private function matchResult($q)
    {
        $user = \Yii::$app->getUser()->identity;

        $q = \Yii::$app->sphinx->escapeMatchValue($q);


        $models = self::find()
            ->match((new MatchExpression())
                ->match(['first_name' => $q])
                ->orMatch(['last_name' => $q])
            )
            ->where(['me_id' => $user->getId()])
            ->all();

        if (!empty($this->getItemsPerPage())) {
            $this->setPagination($this->getItemsPerPage(), $this->getPage());
            $models = array_slice($models, $this->getOffset(), $this->getLimit());
        }

        return $models;
    }

    public function getSearchResult($q)
    {
        $result = [];

        $data = $this->matchResult($q);

        foreach ($data as $model) {
            $result[$model->he_id] = [
                'id' => $model->he_id,
                'first_name' => $model->first_name,
                'last_name' => $model->last_name,
                'slug' => $model->slug,
                'avatar' => $model->getAvatar('32x32', $model->he_id),
                'relation' => $model->relation
            ];
        }

        return $result;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }
}
