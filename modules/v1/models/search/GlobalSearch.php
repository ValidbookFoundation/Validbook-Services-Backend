<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\search;

use app\modules\v1\traits\PaginationTrait;

class GlobalSearch
{

    use PaginationTrait;

    public $q;

    public $searchModels;


    public function __construct($q)
    {
        $this->q = \Yii::$app->sphinx->escapeMatchValue($q);
    }

    public function addSearchModel(Search $search)
    {
        $this->searchModels[$search->getClassName()] = $search;
    }

    public function getSearchResult()
    {
        $user = \Yii::$app->getUser()->identity;

        $this->setPagination(10, $this->getPage());

        $data = [];

        /** @var Search $model */
        if ($user !== null) {
            if (isset($this->searchModels['SearchFollow'])) {
                $model = $this->searchModels['SearchFollow'];
                $result = $model->getSearchResult($this->q);
                $data['users'] = $result;
                if (isset($this->searchModels['SearchUser'])) {
                    $model = $this->searchModels['SearchUser'];
                    $newResult = $result + $model->getSearchResult($this->q);
                    $data['users'] = $newResult;
                    if (isset($this->searchModels['User'])) {
                        $model = $this->searchModels['User'];
                        $newResult = $newResult + $model->getSearchResult($this->q);
                        $data['users'] = $newResult;
                    }
                }


                $data['users'] = array_merge([], $data['users']);

                $data['users'] = array_slice($data['users'], $this->getOffset(), $this->getLimit());


            }
            if (isset($this->searchModels['SearchBook'])) {
                $model = $this->searchModels['SearchBook'];
                $result = $model->getSearchResult($this->q);
                $data['books'] = $result;

                if (isset($this->searchModels['Book'])) {
                    $model = $this->searchModels['Book'];
                    $newResult = $result + $model->getSearchResult($this->q);
                    $data['books'] = $newResult;

                }
                $data['books'] = array_merge([], $data['books']);
                $data['books'] = array_slice($data['books'], $this->getOffset(), $this->getLimit());

            }
            if (isset($this->searchModels['SearchStory'])) {
                $this->setPagination(6, $this->getPage());
                $model = $this->searchModels['SearchStory'];
                $result = $model->getSearchResult($this->q);
                $data['stories'] = $result;
                if (isset($this->searchModels['Story'])) {
                    $model = $this->searchModels['Story'];
                    $newResult = $result + $model->getSearchResult($this->q);
                    $data['stories'] = $newResult;
                }

                $data['stories'] = array_merge([], $data['stories']);
                $data['stories'] = array_slice($data['stories'], $this->getOffset(), $this->getLimit());
            }
            if (isset($this->searchModels['SearchDocument'])) {
                $this->setPagination(6, $this->getPage());
                $model = $this->searchModels['SearchDocument'];
                $result = $model->getSearchResult($this->q);
                $data['documents'] = $result;
                if (isset($this->searchModels['Document'])) {
                    $model = $this->searchModels['Document'];
                    $newResult = $result + $model->getSearchResult($this->q);

                    $data['documents'] = $newResult;
                }

                $data['documents'] = array_merge([], $data['documents']);
                $data['documents'] = array_slice($data['documents'], $this->getOffset(), $this->getLimit());
            }
        }
        return $data;
    }
}