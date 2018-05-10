<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\Queue;

class SendNotificationEmail extends BaseObject implements Job
{
    public $notificationClassName;
    public $message;
    public $email;
    public $subject;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $view = 'notification';

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