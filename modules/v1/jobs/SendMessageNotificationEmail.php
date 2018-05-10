<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\conversation\ConversationToMessageUser;
use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\Queue;

class SendMessageNotificationEmail extends BaseObject implements Job
{
    public $message;
    public $email;
    public $receiverId;
    public $conversationId;
    public $subject;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $view = 'notification';

        $conversation = ConversationToMessageUser::findOne(
            ['conversation_id' => $this->conversationId,
                'user_id' => $this->receiverId,
                'is_seen' => 0
            ]);

        if (!empty($conversation)) {
            \Yii::$app->mailer
                ->compose($view, [
                    'message' => $this->message
                ])
                ->setFrom(["support@validbook.org" => "Validbook"])
                ->setSubject($this->subject)
                ->setTo($this->email)
                ->send();
        }
    }
}