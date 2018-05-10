<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\GuestRestController as Controller;
use app\modules\v1\models\Profile;
use app\modules\v1\models\Role;
use app\modules\v1\models\User;
use Yii;
use yii\db\Transaction;

/**
 * Class RegistrationController
 * @package app\modules\v1\controllers
 */
class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $user = new User(['scenario' => User::SCENARIO_REGISTER]);
        $profile = Yii::createObject(Profile::className());

        // load post data
        $address = Yii::$app->request->post('address');
        $recoveryAddress = Yii::$app->request->post('backup_address');
        $firstName = Yii::$app->request->post('first_name');
        $lastName = Yii::$app->request->post('last_name');

        $user->first_name = $firstName;
        $user->last_name = $lastName;

        if ($user->validate()) {

            $transaction = Yii::$app->db->beginTransaction(
                Transaction::SERIALIZABLE
            );

            try {
                // perform registration
                $role = new Role();
                $user->setRegisterAttributes($role::ROLE_USER);
                $user->save();
                $user->createDefaultChannelAndBookAndBox();

                $profile->full_name = $user->first_name . " " . $user->last_name;
                $profile->setUser($user->id)->save();

                $user->createIdentity($address, $recoveryAddress);
                $user->createEmailEntity();

                $transaction->commit();

                $response = [
                    'token' => $user->access_token
                ];

                return $this->success(array_merge($user->getFormattedData(), $response), 201);

            } catch (\Exception $e) {
                $transaction->rollBack();

                return $this->failure($e->getMessage());
            }

        } else {
            // validation failed: $errors is an array containing error messages
            return $this->failure($user->errors);
        }
    }
}