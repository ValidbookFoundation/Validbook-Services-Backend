<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\GuestRestController as Controller;
use app\modules\v1\models\DeactivateUser;
use app\modules\v1\models\identity\Identity;
use app\modules\v1\models\oauth2\IdToken;
use app\modules\v1\models\oauth2\OauthAccessTokens;
use app\modules\v1\models\oauth2\OauthAuthorizationCodes;
use app\modules\v1\models\oauth2\OauthClients;
use app\modules\v1\models\oauth2\OauthRequestToken;
use app\modules\v1\models\User;
use app\modules\v1\models\UserKey;
use Yii;
use yii\db\StaleObjectException;

/**
 * Class AuthController
 * @package app\modules\v1\controllers
 */
class AuthController extends Controller
{

    public function actionAuthorizeToken()
    {
        $requestToken = new OauthRequestToken();

        $post = Yii::$app->request->post();

        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');

        $requestToken->setAttributes($post);

        if ($authHeader !== null && preg_match('/^Basic\s+(.*?)$/', $authHeader, $matches)) {
            $clientData = base64_decode($matches[1]);
            $clientDataArray = explode(":", $clientData);
            $requestToken->client_secret = $clientDataArray[1];
        }


        if (!$requestToken->validate()) {
            return $this->failure($requestToken->errors, 422);
        }

        /** @var OauthClients $client */
        $client = OauthClients::find()
            ->where([
                'client_id' => $requestToken->client_id,
                'client_secret' => $requestToken->client_secret])
            ->one();

        if ($client == null) {
            return $this->failure("Invalid client", 400);
        }

        if ($requestToken->grant_type !== $client->grant_types) {
            return $this->failure("Invalid grant", 400);
        }


        /** @var OauthAuthorizationCodes $codeModel */
        $codeModel = OauthAuthorizationCodes::find()
            ->where([
                'authorization_code' => $requestToken->code,
                'client_id' => $requestToken->client_id,
                'redirect_uri' => $requestToken->redirect_uri,
                'scope' => $client->scope
            ])
            ->andWhere(['>', 'expires', time()])
            ->one();

        if ($codeModel == null) {
            return $this->failure('Invalid request', 400);
        }

        /** @var OauthAccessTokens $currentToken */
        $currentToken = OauthAccessTokens::find()
            ->where(['client_id' => $codeModel->client_id, 'user_id' => $codeModel->user_id, 'scope' => $client->scope])
            ->andWhere(['>', 'expires', time()])
            ->one();


        $tokenType = 'bearer';

        if (!empty($currentToken)) {

            $result = [
                'access_token' => $currentToken->access_token,
                'expires_in' => 60 * 60,
                'token_type' => $tokenType,
                'id_token' => null
            ];

            if ($client->client_id === getenv("CLOUD_CLIENT_ID")) {

                    $idToken = new IdToken(
                        $client->client_id,
                        $client->scope,
                        $currentToken->user_id,
                        $currentToken->access_token,
                        $codeModel->nonce
                    );

                    $currentToken->id_token = $idToken->getIdToken();
                    $currentToken->update();

                    $result['id_token'] = $idToken->getIdToken();
                }

            return $this->shortSuccess($result);
        }

        $token = new OauthAccessTokens();

        $token->client_id = $codeModel->client_id;
        $token->access_token = Yii::$app->security->generateRandomString();
        $token->user_id = $codeModel->user_id;
        $token->scope = $codeModel->scope;
        $token->expires = time() + 60 * 60;

        if (!$token->save()) {
            return $this->failure($token->errors);
        }


        $result = [
            'access_token' => $token->access_token,
            'expires_in' => 60 * 60,
            'token_type' => $tokenType,
            'id_token' => null
        ];

        if ($client->client_id === getenv("CLOUD_CLIENT_ID")) {
            $idToken = new IdToken(
                $client->client_id,
                $client->scope,
                $token->user_id,
                $token->access_token,
                $codeModel->nonce);

            $token->id_token = $idToken->getIdToken();
            $token->update();

            $codeModel->delete();

            $result['id_token'] = $idToken->getIdToken();
        }

        return $this->shortSuccess($result);
    }

    public function actionGetRandom($address)
    {
        $identity = Identity::find()
            ->where(['public_address' => $address])
            ->orWhere(['recovery_address' => $address])
            ->one();

        if ($identity === null) {
            return $this->failure("Identity does not exist");
        }

        $random = random_int(0, 2147483647);
        $identity->updateAttributes([
            'random_number' => $random
        ]);

        return $this->success(['message' => (string)$identity->random_number]);
    }

    public function actionLogin()
    {
        $signature = Yii::$app->request->post('signature');
        $address = Yii::$app->request->post('address');
        $response['generate_keys'] = false;

        if (empty($signature) || empty($address)) {
            return $this->failure('Signature and address can not be empty!', 422);
        }

        /** @var User $user */
        $identity = Identity::find()
            ->where(['public_address' => $address])
            ->orWhere(['recovery_address' => $address])
            ->one();

        if ($identity === null) {
            return $this->failure("Identity does not exist");
        }

        $user = User::findOne($identity->user->id);

        if (empty($user)) {
            return $this->failure('Address is not found');
        }

        if (!$user->status) {
            $user->status = User::STATUS_ACTIVE;
            $modelDeactivate = DeactivateUser::findOne(['user_id' => $user->getId()]);
            try {
                $modelDeactivate->delete();
            } catch (StaleObjectException $e) {
            } catch (\Exception $e) {
                return $e;
            }
            $user->update();
        }

        if (!$user->validateSig($signature, $address)) {
            //if identity use recovery_address to auth, lets send command to FE to generate new keys
            if ($identity->recovery_address === $address) {
                $response['generate_keys'] = true;

                $identity->revokeKeys();
            }

            return $this->failure('Signature is not valid', 422);
        }

        $response['token'] = $user->access_token;

        return $this->success(array_merge($user->getFormattedData(), $response));

    }
}