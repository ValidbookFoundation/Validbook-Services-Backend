<?php

namespace backend\controllers;

use backend\models\search\UserSearch;
use Yii;
use yii\web\Controller;
use kartik\export\ExportMenu;

class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        // check for admin permission (`tbl_role.can_admin`)
        // note: check for Yii::$app->user first because it doesn't exist in console commands (throws exception)
        if (!empty(Yii::$app->user) && !Yii::$app->user->can("admin")) {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        parent::init();
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'color',
            'publish_date',
            'status',
            ['class' => 'yii\grid\ActionColumn'],
        ];

        // Renders a export dropdown menu
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns
        ]);
    }


}