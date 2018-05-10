<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\kds;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "kds_rolling_custodial_balance".
 *
 * @property integer $id
 * @property string $hc_address
 * @property integer $balance
 * @property integer $timestamp_of_calc
 */
class KdsRollingCustodialBalance extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kds_rolling_custodial_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hc_address', 'balance'], 'required'],
            [['balance', 'timestamp_of_calc'], 'integer'],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['timestamp_of_calc'],
                ],
            ],
        ];
    }

    public function saveAddBalance($hcAddress, $balance)
    {

        $currentBalance = self::checkBalance($hcAddress);

        $sumBalance = bcadd((string)$currentBalance, $balance, 0);

        $this->balance = $sumBalance;
        $this->hc_address = $hcAddress;
    }

    public function saveSubBalance($hcAddress, $balance)
    {

        $currentBalance = self::checkBalance($hcAddress);

        $subsBalance = bcsub((string)$currentBalance, $balance, 0);

        $this->balance = $subsBalance;
        $this->hc_address = $hcAddress;
    }

    public static function checkBalance($hcAddress)
    {
        $subQuery = (new Query())
            ->select('max(timestamp_of_calc)')
            ->from('kds_rolling_custodial_balance')
            ->where(['hc_address' => $hcAddress]);

        $currentBalance = (new Query())
            ->select('balance')
            ->from('kds_rolling_custodial_balance')
            ->where(['hc_address' => $hcAddress])
            ->andWhere(['timestamp_of_calc' => $subQuery])
            ->one();

        if ($currentBalance['balance'] == null) {
            $currentBalance['balance'] = 0;
        }

        return $currentBalance['balance'];
    }

}