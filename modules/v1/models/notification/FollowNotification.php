<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;

use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\helpers\StringHelper;

class FollowNotification extends Notification implements NotificationInterface
{

    public function getUrl(BaseObject $object)
    {
        $link = $object->url;
        return $link;
    }

    public function getText(User $sender, BaseObject $object = null)
    {

        $text = "<a>" . $sender->getFullName() . "</a><span>followed you.<span>";

        return $text;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getTextForEmail(User $receiver, User $sender, BaseObject $object)
    {
        $text = "Hi " . $receiver->first_name . ",<br><br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $sender->getUrl() . "\">" . $sender->getFullName() . "</a>, followed you on " . \Yii::$app->id . ".<br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $sender->getUrl() . "\" class=\"link\"> See " . $sender->getFullName() . " on " . \Yii::$app->id . ".</a>
";
        return $text;
    }

    public function getEmailSubject(User $receiver)
    {
        return $receiver->getFullName() . ', you have a new follower on ' . \Yii::$app->id . '!';
    }
}