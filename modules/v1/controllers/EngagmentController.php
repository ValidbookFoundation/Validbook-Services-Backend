<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\Profile;
use app\modules\v1\models\User;
use Yii;

class EngagmentController extends Controller
{

    public function actionProfile()
    {
        /** @var User $userModel */
        $userModel = Yii::$app->user->identity;

        if(!$this->hasOwnerAccessRights(Profile::className(), 'user_id', $userModel->getId()))
            return $this->failure("You are not allowed to perform this action", 401);

        /** @var Profile $profile */
        $profile = Profile::find()->where(['user_id' => $userModel->getId()])->one();
        if($profile !== null) {
            // load post data
            $post = Yii::$app->request->post();

            $profile->load($post, '');
            $profile->full_name = $profile->first_name. " ".$profile->last_name;

            if ($profile->validate()) {

                $userModel->scenario = User::SCENARIO_PROFILE;
                $userModel->first_name = $profile->first_name;
                $userModel->last_name = $profile->last_name;
                if(!$userModel->save()) {
                    // validation failed: $errors is an array containing error messages
                    return $this->failure($userModel->errors);
                }

                $profile->save();
                $params = ['slug' => $userModel->slug];

                return $this->success($profile->formatResponseData($params), 201);

            } else {
                // validation failed: $errors is an array containing error messages
                return $this->failure($profile->errors);
            }
        }

    }
}