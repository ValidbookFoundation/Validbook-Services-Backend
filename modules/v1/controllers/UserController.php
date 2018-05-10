<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\DeactivateUser;
use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\forms\ChangePasswordForm;
use app\modules\v1\models\oauth2\OauthAccessTokens;
use app\modules\v1\models\oauth2\OauthAuthorizationCodes;
use app\modules\v1\models\oauth2\OauthClients;
use app\modules\v1\models\Profile;
use app\modules\v1\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

/**
 * Class UserController
 * @package app\modules\v1\controllers
 */
class UserController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = array_merge($behaviors['authenticator']['except'], ['stories', 'requested-user', 'client-info']);

        return $behaviors;
    }


    public function actionLogout()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        if ($user->refreshToken()) {
            Yii::$app->user->logout();

            return $this->success();
        }
        return $this->failure($user->errors, 422);

    }


    public function actionStories($userSlug)
    {
        $page = Yii::$app->request->get('page', 1);

        /** @var User $model */
        $model = User::find()->where(['slug' => $userSlug, 'status' => User::STATUS_ACTIVE])->one();

        if (empty($model)) {
            return $this->failure("User doesn't exist");
        }


        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $model->setPage($page);

        if (Yii::$app->getUser()->getId() == $model->getId()) {
            return $this->success($model->getMyStories());
        } else {
            return $this->success($model->getUserStories($model->id));
        }

    }

    public function actionRequestedUser($userSlug)
    {

        $authUserSlug = Yii::$app->request->get("auth_user_slug", null);

        /** @var User $model */
        $model = User::find()->where(['slug' => $userSlug, 'status' => User::STATUS_ACTIVE])->one();

        $bookIds = ArrayHelper::getColumn(Book::findAll(['author_id']), 'id');

        if ($model !== null) {
            $countFollowsBook = (int)FollowBook::find()
                ->where(['user_id' => Yii::$app->user->getId(),
                    'book_id' => $bookIds,
                    'is_follow' => 1,
                    'is_block' => 0
                ])->count();

            return $this->success(array_merge($model->getFormattedData($authUserSlug), ['count_follows_book' => $countFollowsBook]));
        } else
            return $this->failure("User doesn't exist");
    }

    public function actionAuthorizedUser()
    {
        /** @var User $model */
        $model = Yii::$app->user->identity;

        if ($model !== null) {
            return $this->success($model->getFormattedData());
        } else
            return $this->failure("User doesn't exist");
    }

    public function actionProfile($userSlug)
    {
        $user = User::findOne(['slug' => $userSlug]);
        if (empty($user)) {
            return $this->failure('User does not exist');
        }
        $profile = $user->profile->formatResponseData();

        return $this->success($profile);
    }

    public function actionChangePassword()
    {
        $currentPassword = Yii::$app->request->post('current_password');
        $newPassword = Yii::$app->request->post('new_password');
        $confirmPassword = Yii::$app->request->post('confirm_password');

        try {
            $model = new ChangePasswordForm($currentPassword, $newPassword, $confirmPassword);
        } catch (InvalidParamException $e) {
            return $this->failure($e->getMessage(), $e->getCode());
        }

        if ($model->validate() && $model->changePassword()) {
            return $this->success();
        } else {
            return $this->failure($model->errors);
        }
    }

    public function actionDeactivate()
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        if (empty($user)) {
            return $this->failure("User does not exist");
        }

        $user->status = 0;
        if ($user->update()) {
            $deactivateUser = new DeactivateUser();
            $deactivateUser->user_id = $user->getId();
            $deactivateUser->time_expired = time() + 60 * 24 * 60 * 60;
            $deactivateUser->save();
            Yii::$app->user->logout();
            return $this->success();
        }

        return $this->failure($user->errors);
    }

    public function actionOriginalAvatar($userSlug)
    {

        $user = User::findOne(['slug' => $userSlug, 'status' => User::STATUS_ACTIVE]);

        if (empty($user)) {
            return $this->failure('User not found');
        }

        return $this->success(['avatar' => $user->profile->avatar]);
    }

    public function actionAuthorizeClient()
    {
        $clientId = Yii::$app->request->post('client_id');
        $responseType = Yii::$app->request->post('response_type');
        $redirectUrl = Yii::$app->request->post('redirect_uri');
        $scope = Yii::$app->request->post("scope");
        $nonce = Yii::$app->request->post("nonce");
        $state = Yii::$app->request->post("state");

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if (empty($user)) {
            return $this->failure("User does not exists");
        }

        if (empty($clientId)) {
            return $this->failure('client_id is required', 400);
        }

        $client = OauthClients::findOne(['client_id' => $clientId]);

        if ($client == null) {
            return $this->failure('Client does not exists');
        }

        if ($scope !== $client->scope) {
            return $this->failure('invalid scope', 400);
        }

        if ($responseType !== "code") {
            return $this->failure('response_type invalid', 400);
        }

        /** @var OauthAuthorizationCodes $currentAuthCode */
        $currentAuthCode = OauthAuthorizationCodes::find()
            ->where(['client_id' => $clientId, 'scope' => $client->scope])
            ->andWhere([">", 'expires', time()])
            ->one();

        if (!empty($currentAuthCode)) {
            OauthAccessTokens::deleteAll(['client_id' => $currentAuthCode->client_id, 'user_id' => $currentAuthCode->user_id]);
            $currentAuthCode->delete();
        }

        $codeAuthorize = new OauthAuthorizationCodes();

        $codeAuthorize->authorization_code = Yii::$app->security->generateRandomString(16);
        $codeAuthorize->user_id = $user->getId();
        $codeAuthorize->redirect_uri = $redirectUrl;
        $codeAuthorize->expires = time() + 60 * 10;
        $codeAuthorize->client_id = $clientId;
        $codeAuthorize->scope = $scope;
        $codeAuthorize->nonce = $nonce;
        $codeAuthorize->state = $state;

        if ($client->client_id === getenv("WIKI_CLIENT_ID")) {
            $redirect = ['redirect' => $codeAuthorize->redirect_uri . "&" . 'code=' . $codeAuthorize->authorization_code];
        } else {
            $redirect = ['redirect' => $codeAuthorize->redirect_uri . "?" . 'code=' . $codeAuthorize->authorization_code . '&state=' . $codeAuthorize->state];
        }

        if ($codeAuthorize->save()) {
            return $this->success($redirect);

        }
        return $this->failure($codeAuthorize->errors);
    }

    public function actionChangeCalmMode()
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        if (empty($user)) {
            return $this->failure("User does not exist");
        }

        $calmMode = Yii::$app->request->post('calm_mode_notifications');

        if ($calmMode === null) {
            return $this->failure("value of calm mode can not be empty", 422);
        }

        /** @var Profile $profile */
        $profile = $user->getProfile();

        $profile->calm_mode_notifications = $calmMode;

        if (!$profile->update()) {
            return $this->failure($profile->errors, 422);
        }

        return $this->success(['calm_mode_notifications' => $profile->calm_mode_notifications]);
    }
}