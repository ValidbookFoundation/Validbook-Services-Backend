<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\redis\ChannelStory;
use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\Queue;

class UpdateFillChannelJob extends BaseObject implements Job
{

    public $channelId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $channelStories = ChannelStory::findAll(['channel_id' => $this->channelId]);

        foreach ($channelStories as $channelStory) {
            $channelStory->is_blocked = false;
            $channelStory->update();
        }
    }
}