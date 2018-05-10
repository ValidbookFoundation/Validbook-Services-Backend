<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\kds;

use app\modules\v1\models\card\Card;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "kds_origin_to_vhc_daily_incoming_custodial_records".
 *
 * @property integer $id
 * @property string $vhc_address
 * @property integer $kds_amount
 * @property integer $timestamp
 */
class KdsDailyIncoming extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kds_origin_to_vhc_daily_incoming_custodial_records';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vhc_address', 'kds_amount'], 'required'],
            [['kds_amount', 'timestamp'], 'integer'],
            [['vhc_address'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['timestamp'],
                ],
            ],
        ];
    }

    public static function setIncomingRecords()
    {
        $currentKdsPerDay = (string)DimKdsPerDay::getCurrentKdsPerDay();

        $validCards = Card::findAll(['is_valid' => true]);

        $countVHC = count($validCards);


        if ($countVHC !== 0) {

            /** @var $FmodBalanceModel $FmodBalanceModel */
            $FmodBalanceModel = KdsFmodBalance::find()->having('max(timestamp)')->one();

            if ($FmodBalanceModel === null) {
                $FmodBalance = 0;
            } else {
                $FmodBalance = $FmodBalanceModel->kds_fmod;
            }

            $currentKdsPerDayWithFmod = bcadd($currentKdsPerDay, (string)$FmodBalance);

            $currentKdsPerDayForHC = bcdiv($currentKdsPerDayWithFmod, (string)$countVHC, 0);
            $checkFmod = bcmod($currentKdsPerDay, (string)$countVHC);

            $transaction = KdsFmodBalance::getDb()->beginTransaction();
            try {
                $newFmodModel = new KdsFmodBalance();
                $newFmodModel->kds_fmod = $checkFmod;
                $newFmodModel->timestamp = time();
                $newFmodModel->save();
                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }


            $checkKdsPerDay = bcadd(bcmul($currentKdsPerDayForHC, (string)$countVHC, 0), $checkFmod);

            if (bccomp($currentKdsPerDay, $checkKdsPerDay) === 0) {
                foreach ($validCards as $humanCard) {
                    $transaction = self::getDb()->beginTransaction();
                    try {
                        $model = new self();
                        $model->vhc_address = $humanCard->public_address;
                        $model->kds_amount = $currentKdsPerDayForHC;
                        $model->save();
                        $transaction->commit();
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        throw $e;
                    }

                    $transactionNext = KdsRollingCustodialBalance::getDb()->beginTransaction();
                    try {
                        $modelCustodialBalance = new KdsRollingCustodialBalance();
                        $modelCustodialBalance->saveAddBalance($humanCard->public_address, $currentKdsPerDayForHC);
                        $modelCustodialBalance->save();
                        $transactionNext->commit();
                    } catch (\Throwable $e) {
                        $transactionNext->rollBack();
                        throw $e;
                    }

                    $transaction2 = KdsTransactionRecords::getDb()->beginTransaction();
                    try {
                        $model = new KdsTransactionRecords();
                        $model->hc_address = $humanCard->public_address;
                        $model->kds_amount = $currentKdsPerDayForHC;
                        $model->type = KdsTransactionRecords::TYPE_INCOMING;
                        $model->save();
                        $transaction2->commit();
                    } catch (\Throwable $e) {
                        $transaction2->rollBack();
                        throw $e;
                    }
                }
            }

        }

    }

}