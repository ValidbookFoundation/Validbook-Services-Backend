<?php

namespace backend\controllers;

use amnah\yii2\user\controllers\AdminController as BaseAdminController;

use amnah\yii2\user\models\search\UserSearch;
use Yii;

class AdminController extends BaseAdminController
{

    /**
     * List all User models
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var \amnah\yii2\user\models\search\UserSearch $searchModel */
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

}