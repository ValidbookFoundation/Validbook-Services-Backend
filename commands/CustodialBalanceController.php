<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use app\modules\v1\models\kds\KdsDailyIncoming;
use app\modules\v1\models\kds\KdsWithDrawal;
use yii\console\Controller;
use yii\helpers\Console;

class CustodialBalanceController extends Controller
{
    public function actionIncoming()
    {
        KdsDailyIncoming::setIncomingRecords();
    }

    public function actionDrawal($pass)
    {
        $model = new KdsWithDrawal();
        if ($model->completeDrawal($pass)) {
            $this->stdout("true\n", Console::BOLD);
        }
        $this->stdout("false\n", Console::BOLD);
    }
}