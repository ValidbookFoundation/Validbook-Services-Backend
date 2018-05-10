<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\modules\v1\models\User;
use yii\base\BaseObject;

interface NotificationInterface
{
    public function getText(User $sender, BaseObject $object);

    public function getUrl(BaseObject $object);

    public function getClassName();

}