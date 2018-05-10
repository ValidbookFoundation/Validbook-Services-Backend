<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\helpers\KdsHelper;
use app\modules\v1\models\card\Card;
use app\modules\v1\models\kds\KdsRollingCustodialBalance;
use app\modules\v1\models\kds\KdsTransactionRecords;
use app\modules\v1\models\kds\KdsWithDrawal;
use app\modules\v1\models\User;

/**
 * Class WalletController
 * @package app\modules\v1\controllers
 */
class WalletController extends Controller
{
    public function actionRequestDrawals()
    {
        $hcAddress = \Yii::$app->request->post("hc_address");
        $amount = \Yii::$app->request->post('amount');

        if ($amount == null || $amount == 0) {
            return $this->failure("amount can not be empty");
        }
        /** @var User $modelUser */
        $modelUser = \Yii::$app->getUser()->identity;

        $card = Card::findOne(['public_address' => $modelUser->identity->public_address]);

        if (empty($card)) {
            return $this->failure("Card does not exist");
        }

        $currentDrawalOpen = KdsWithDrawal::findOne(['status' => KdsWithDrawal::STATUS_OPENED, 'hc_address' => $hcAddress]);

        if (!empty($currentDrawalOpen)) {
            $currentDrawalOpen->status = KdsWithDrawal::STATUS_CANCELED;
            $currentDrawalOpen->update();
        }

        $currentDrawalPending = KdsWithDrawal::findOne(['status' => KdsWithDrawal::STATUS_PENDING, 'hc_address' => $hcAddress]);

        if (!empty($currentDrawalPending)) {
            return $this->failure("You can not drawal your KDS twice", 403);
        }

        $model = new KdsWithDrawal();

        if (!$model->isDrawable($hcAddress, $amount)) {
            return $this->failure("You can not drawal KDS from your custodial balance", 422);
        }

        $model->hc_address = $card->public_address;
        $model->kds_amount = $amount;

        if (!$model->save()) {
            return $this->failure($model->errors);
        }

        return $this->success(['data' => true], 201);

    }

    public function actionCustodialBalance()
    {
        /** @var User $user */
        $user = \Yii::$app->getUser()->identity;

        $balance = KdsRollingCustodialBalance::checkBalance($user->identity->public_address);

        $result = [
            "custodial_balance" => KdsHelper::getKDS($balance)
        ];
        return $this->success($result);
    }

    public function actionTransRecords()
    {
        $page = \Yii::$app->request->get("page", 1);

        if (!is_numeric($page))
            return $this->failure("Invalid parameter 'page'", 400);

        /** @var User $user */
        $user = \Yii::$app->getUser()->identity;

        $model = new KdsTransactionRecords();
        $model->setPage($page);
        $model->setItemsPerPage(10);

        $res = $model->getTransactions($user->identity->public_address);

        return $this->success($res);

    }

}