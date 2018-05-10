<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\kds;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "dim_kds_per_day".
 *
 * @property integer $id
 * @property integer $year
 * @property integer $kds_per_day
 */
class DimKdsPerDay extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dim_kds_per_day';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'kds_per_day'], 'required'],
            [['year', 'kds_per_day'], 'integer'],
        ];
    }

    public static function getCurrentKdsPerDay()
    {
        $cYear = (int)\Yii::$app->formatter->asDate(time(), 'yyyy');

        if ($cYear == 2017) {
            $cYear = \Yii::$app->formatter->asDate(time() + 365 * 24 * 60 * 60, 'yyyy');
        }

        $currentKdsPerDay = self::findOne(['year' => $cYear]);

        return $currentKdsPerDay->kds_per_day;
    }

}

