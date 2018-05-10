<?php

namespace backend\controllers;

use amnah\yii2\user\controllers\DefaultController as BaseDefaultController;

use amnah\yii2\user\models\search\UserSearch;
use Yii;

class DefaultController extends BaseDefaultController
{
    /**
     * Display index - debug page, login page, or account page
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(["/user/login"]);
        } else {
            return $this->redirect(["/user/admin"]);
        }
    }

    /**
     * Display login page
     */
    public function actionLogin()
    {
        /** @var \amnah\yii2\user\models\forms\LoginForm $model */
        $model = $this->module->model("LoginForm");

        // load post data and login
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            $returnUrl = $this->performLogin($model->getUser(), $model->rememberMe);
            return $this->redirect($returnUrl);
        }

        return $this->render('login', compact("model"));
    }

    public function actionRegister()
    {
        return $this->redirect(["/user/login"]);
    }
}