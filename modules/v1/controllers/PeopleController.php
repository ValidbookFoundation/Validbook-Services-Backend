<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\People;
use app\modules\v1\models\User;
use Yii;

/**
 * Class PeopleController
 * @package app\modules\v1\controllers
 */
class PeopleController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
       // $behaviors['authenticator']['except'] = ['following', 'followers', 'block'];

        return $behaviors;
    }

    public function actionSuggested()
    {
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;

        $peoples = new People($modelUser->getId(), 'suggested', $page);
        $userIds = $peoples->getUserIds();

        return $this->success(User::formatUserCard($userIds, Yii::$app->user->id));

    }

    public function actionFollowing()
    {
        $requestedUserSlug = Yii::$app->request->get('user_slug');
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page))
            return $this->failure("Invalid parameter 'page'", 400);

        if (!empty($requestedUserSlug)) {
            $modelUser = User::find()->where(['slug' => $requestedUserSlug, 'status' => User::STATUS_ACTIVE])->one();
        } else {
            $modelUser = Yii::$app->user->identity;
        }

        /** @var User $modelUser */
        $modelUser->setPage($page);
        $modelUser->setItemsPerPage(16);
        $followings = $modelUser->followingList();

        return $this->success([
            'count' => $modelUser->countFollowing(),
            'users' => $followings
        ]);
    }

    public function actionFollowers()
    {
        $requestedUserSlug = Yii::$app->request->get('user_slug' );
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page))
            return $this->failure("Invalid parameter 'page'", 400);

        if (!empty($requestedUserSlug)) {
            $modelUser = User::find()->where(['slug' => $requestedUserSlug, 'status' => User::STATUS_ACTIVE])->one();
        } else {
            $modelUser = Yii::$app->getUser()->identity;
        }

        /** @var User $modelUser */
        $modelUser->setPage($page);
        $modelUser->setItemsPerPage(16);
        $followers = $modelUser->followersList();

        return $this->success([
            'count' => $modelUser->countFollowers(),
            'users' => $followers
        ]);

    }

    public function actionBlock()
    {
        $requestedUserSlug = Yii::$app->request->get('user_slug');
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        if (!empty($requestedUserSlug)) {
            $modelUser = User::find()->where(['slug' => $requestedUserSlug, 'status' => User::STATUS_ACTIVE])->one();
        } else {
            $modelUser = Yii::$app->user->identity;
        }

        if ($modelUser === null) {
            return $this->failure("User model not found");
        }

        $peoples = new People($modelUser->getId(), 'block');
        $userIds = $peoples->getUserIds();

        return $this->success(User::formatUserCard($userIds, Yii::$app->user->id));
    }

    public function actionAllPeople()
    {
        $requestedUserSlug = Yii::$app->request->get('user_slug');
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        if (!empty($requestedUserSlug)) {
            $modelUser = User::find()->where(['slug' => $requestedUserSlug])->one();
        } else {
            $modelUser = Yii::$app->user->identity;
        }

        if ($modelUser === null) {
            return $this->failure("User model not found");
        }

        $peoples = new People($modelUser->getId(), 'all', $page);

        $userIds = $peoples->getUserIds();

        return $this->success(User::formatUserCard($userIds, Yii::$app->user->id));

    }

}