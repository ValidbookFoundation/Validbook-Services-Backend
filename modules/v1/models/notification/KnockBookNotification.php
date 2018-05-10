<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\helpers\StringHelper;

class KnockBookNotification extends Notification implements NotificationInterface
{
    public function getUrl(BaseObject $object)
    {
        $link = $object->getUrl() . '/requests-to-open-book';
        return $link;
    }

    public function getText(User $sender, BaseObject $object = null)
    {

        $text = "<a>" . $sender->getFullName() . "</a><span>knocked on your</span>book.";

        return $text;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getTextForEmail(User $receiver, User $sender, BaseObject $object)
    {

        $urlObject = \Yii::$app->params['siteUrl'] . "/" . $receiver->slug . "/books/" . $object->getUrl();

        $text = "Hi " . $receiver->first_name . ",<br><br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $sender->getUrl() . "\">" . $sender->getFullName() . "</a>, requested access to your " . $object->name . " book. " . \Yii::$app->id . ".<br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $urlObject . "\" class=\"link\"> See " . $object->name . " on " . \Yii::$app->id . ".</a>
";

        return $text;
    }

    public function getEmailSubject(User $sender)
    {
        return $sender->getFullName() . ' requested access to your book on' . \Yii::$app->id;
    }
}