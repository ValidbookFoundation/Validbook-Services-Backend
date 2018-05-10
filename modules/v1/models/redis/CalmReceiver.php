<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;


use yii\redis\ActiveRecord;

/**
 * Class CalmReceiver
 * @package app\modules\v1\models\redis
 * @property string $sender
 * @property integer $id
 * @property string $receiver_id
 * @property string $name_object
 * @property string $object
 * @property string $receivers
 */
class CalmReceiver extends ActiveRecord
{
    public function attributes()
    {
        return ['receivers', 'sender', 'receiver_id', 'object', 'name_object', 'id'];
    }
}