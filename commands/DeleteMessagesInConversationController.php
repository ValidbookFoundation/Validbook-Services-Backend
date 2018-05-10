<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use app\modules\v1\models\conversation\Conversation;
use app\modules\v1\models\Message;
use app\modules\v1\models\MessageToReceiver;
use yii\console\Controller;

class DeleteMessagesInConversationController extends Controller
{
    public function actionIndex()
    {
        $conversations = Conversation::find()->all();

        $currentTime = time();
        /** @var Conversation $conversation */
        foreach ($conversations as $conversation) {
            $deleteHours = $conversation->hours_delete_messages;
            if ($deleteHours !== 0) {
                $deleteSeconds = $deleteHours * 3600;

                $messages = Message::findAll(['conversation_id' => $conversation->id]);
                foreach ($messages as $message) {
                    $timeDelete = $message->created_at + $deleteSeconds;
                    if ($timeDelete < $currentTime) {
                        MessageToReceiver::deleteAll(['message_id' => $message->id]);
                        $message->delete();
                    }
                }
            }
        }
    }
}