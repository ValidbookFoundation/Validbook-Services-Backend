<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\kds;


use app\modules\v1\traits\GethClientTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "kds_with_drawal_request_from_custodial_account".
 *
 * @property integer $id
 * @property string $hc_address
 * @property integer $kds_amount
 * @property integer $timestamp
 * @property integer $status
 */
class KdsWithDrawal extends ActiveRecord
{
    const STATUS_OPENED = 0;
    const STATUS_PENDING = 1;
    const STATUS_CANCELED = 2;
    const STATUS_COMPLETED = 3;

    use GethClientTrait;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kds_with_drawal_request_from_custodial_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hc_address', 'kds_amount'], 'required'],
            [['kds_amount', 'timestamp', 'status'], 'integer'],
            [['hc_address'], 'string', 'max' => 255],
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

    public function isDrawable($hcAddress, $amount)
    {
        $currentBalance = KdsRollingCustodialBalance::checkBalance($hcAddress);

        if ($currentBalance == 0) {
            return false;
        }

        if (bccomp((string)$currentBalance, (string)$amount, 0) == -1) {
            return false;
        }

        return true;
    }

    public function completeDrawal($pass)
    {
        $drawals = KdsWithDrawal::findAll(['status' => self::STATUS_OPENED]);

        $hotWalletAddress = getenv("HOT_WALLET_ADDRESS");

        foreach ($drawals as $drawal) {
            $transaction = self::getDb()->beginTransaction();
            try {
                if ($this->checkTransaction($hotWalletAddress, $drawal->hc_address, $drawal->kds_amount, $pass)) {
                    $drawal->status = self::STATUS_COMPLETED;
                    $drawal->update();


                    $modelCustodialBalance = new KdsRollingCustodialBalance();
                    $modelCustodialBalance->saveSubBalance($drawal->hc_address, $drawal->kds_amount);
                    $modelCustodialBalance->save();

                    $transaction->commit();
                    return true;
                }
                return false;
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }

            $transaction2 = KdsTransactionRecords::getDb()->beginTransaction();
            try {
                $model = new KdsTransactionRecords();
                $model->hc_address = $drawal->hc_address;
                $model->kds_amount = $drawal->kds_amount;
                $model->type = KdsTransactionRecords::TYPE_REQUEST_DRAWAL;
                $model->save();
                $transaction2->commit();
            } catch (\Throwable $e) {
                $transaction2->rollBack();
                throw $e;
            }
        }
        return false;
    }

}
