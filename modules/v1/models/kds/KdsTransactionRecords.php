<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\kds;


use app\modules\v1\helpers\KdsHelper;
use app\modules\v1\traits\PaginationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "kds_transaction_records".
 *
 * @property integer $id
 * @property integer $type
 * @property string $hc_address
 * @property integer $kds_amount
 * @property integer $timestamp
 */
class KdsTransactionRecords extends ActiveRecord
{

    use PaginationTrait;

    const TYPE_INCOMING = 1;
    const TYPE_REQUEST_DRAWAL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kds_transaction_records';
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'kds_amount', 'timestamp'], 'integer'],
            [['hc_address', 'kds_amount'], 'required'],
            [['hc_address'], 'string', 'max' => 255],
        ];
    }

    public function getFormattedData()
    {
        return [
            "amount" => KdsHelper::getKDS($this->kds_amount),
            "type" => $this->getType(),
            "created" => $this->getTime(),
        ];
    }

    public function getType()
    {
        if ($this->type === self::TYPE_INCOMING) {
            return "+";
        } else {
            return "-";
        }
    }

    public function getTime()
    {
        return \Yii::$app->formatter->asDate($this->timestamp, "dd MMM yyyy");
    }

    public function getTransactions($address)
    {
        $res = [];
        $trans = self::find()
            ->where(['hc_address' => $address])
            ->orderBy('timestamp DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var self $record */
        foreach ($trans as $record) {
            $res[] = $record->getFormattedData();
        }

        return $res;
    }
}