<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\ClientRestController;
use app\modules\v1\models\User;


class ClientController extends ClientRestController
{
    public function actionUserInfo()
    {
        /** @var User $user */
        $user = \Yii::$app->getUser()->identity;

        if (empty($user)) {
            return $this->failure("User not found");
        }

        $result = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar('230x230', $user->getId()),
            'username' => $user->getFullName()
        ];

        return $this->shortSuccess($result);
    }
}