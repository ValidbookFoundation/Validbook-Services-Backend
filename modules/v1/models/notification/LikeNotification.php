<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\helpers\StringHelper;

class LikeNotification extends Notification implements NotificationInterface
{
    public function getUrl(BaseObject $object)
    {
        $link = $object->url;
        return $link;
    }

    public function getText(User $sender, BaseObject $object = null)
    {

        $text = "<a>" . $sender->getFullName() . "</a><span>liked your</span>story.";

        return $text;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getTextForEmail(User $receiver, User $sender, BaseObject $object)
    {
        $urlObject = \Yii::$app->params['siteUrl'] . "/story/" . $object->id;


        $text = "Hi " . $receiver->first_name . ",<br><br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $sender->getUrl() . "\">" . $sender->getFullName() . "</a>, liked your story. " . \Yii::$app->id . ".<br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $urlObject . "\" class=\"link\"> See story on " . \Yii::$app->id . ".</a>
";

        return $text;
    }

    public function getEmailSubject(User $sender)
    {
        return $sender->getFullName() . ' liked your story';
    }
}