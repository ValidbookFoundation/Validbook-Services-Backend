<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\notification;


use app\modules\v1\models\card\Card;
use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\helpers\StringHelper;


class HumanCardNotification extends Notification
{
    public function getText(User $sender, BaseObject $object = null)
    {
        $text = "<a>" . $sender->getFullName() . "</a><span> supported your \"I am human\" claim.  Such support must be provided only by humans and be mutual. If you know that {$sender->first_name}'s \"I am human\" claim is valid, support it back.</span>";
        return $text;
    }

    public function getUrl(BaseObject $object)
    {
        $card = Card::findOne(['public_address' => $object->public_address]);
        $sender  = $card->getOwnerOfCard();
        $link = \Yii::$app->params["siteUrl"] . "/{$sender->slug}/documents/account-card/{$object->public_address}";
        return $link;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function getTextForEmail(User $receiver, User $sender, BaseObject $object)
    {
        $text = "Hi " . $receiver->first_name . ",<br><br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" .$this->getUrl($object) . "\">" . $sender->getFullName() . "</a>, supported your \"I am human\" claim.  Such support must be provided only by humans and be mutual. If you know that {$sender->first_name}'s \"I am human\" claim is valid, support it back. " . \Yii::$app->id . ".<br>
<a style=\"text-decoration: none; font-weight: bold;\" href=\"" . $this->getUrl($object) . "\" class=\"link\"> See account card on " . \Yii::$app->id . ".</a>
";

        return $text;
    }

    public function getEmailSubject(User $sender)
    {
        return 'New message from ' . $sender->getFullName();
    }

}