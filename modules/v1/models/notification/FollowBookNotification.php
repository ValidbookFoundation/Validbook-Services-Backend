<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class FollowBookNotification extends Notification implements NotificationInterface
{
    public function getUrl(BaseObject $object)
    {
        $link = $object->url;
        return $link;
    }

    public function getText(User $sender, BaseObject $object)
    {

        $booksCounts = count(ArrayHelper::getColumn($object->children(1)->all(), 'id'));

        if ($booksCounts > 0) {
            $text = "<a>" . $sender->getFullName() . "</a><span>followed your " . $object->name . " book and its internal books.<span>";
        } else {
            $text = "<a>" . $sender->getFullName() . "</a><span>followed your " . $object->name . " book.<span>";
        }


        return $text;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getTextForEmail(User $receiver, User $sender, BaseObject $object)
    {
        $booksCounts = count(ArrayHelper::getColumn($object->children(1)->all(), 'id'));

        $urlObject = \Yii::$app->params['siteUrl'] . "/" . $receiver->slug. "/books/". $object->getUrl();

        if ($booksCounts > 0) {
            $text = "Hi " . $receiver->first_name . ",<br><br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $sender->getUrl() . "\">" . $sender->getFullName() . "</a>, followed your " . $object->name . " book and its internal books. " . \Yii::$app->id . ".<br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $urlObject . "\" class=\"link\"> See " .  $object->name . " on " . \Yii::$app->id . ".</a>
";
        } else {
            $text = "Hi " . $receiver->first_name . ",<br><br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $sender->getUrl() . "\">" . $sender->getFullName() . "</a>, followed your " . $object->name . " book. " . \Yii::$app->id . ".<br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $urlObject. "\" class=\"link\"> See " . $object->name . " on " . \Yii::$app->id . ".</a>
";
        }
        return $text;
    }

    public function getEmailSubject(User $sender)
    {
        return $sender->getFullName() . ' followed your book on '. \Yii::$app->id . '!';
    }
}