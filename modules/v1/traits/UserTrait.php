<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\traits;

use app\modules\v1\models\oauth2\OauthAccessTokens;
use yii\web\UnauthorizedHttpException;

trait UserTrait
{

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $errorText = "User not found";

        if (!empty($token)) {
            $model = static::findOne([
                'access_token' => $token,
                'status' => self::STATUS_ACTIVE
            ]);
        }

        if($type === "Client"){
            /** @var OauthAccessTokens $userToken */
            $userToken = OauthAccessTokens::find()
                ->where(['access_token' => $token])
                ->andWhere(['>', 'expires', time()])
                ->one();

            $model =  static::findOne($userToken->user_id);
        }

        if (empty($model)) {
            throw new UnauthorizedHttpException($errorText);
        }

        return $model;
    }

    /**
     * @return int|string current user slug
     */
    public function getSlug() {
        return $this->slug;
    }

}