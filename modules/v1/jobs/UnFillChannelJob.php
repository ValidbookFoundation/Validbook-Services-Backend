<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\redis\ChannelStory;
use app\modules\v1\models\story\Story;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\queue\Job;
use yii\queue\Queue;

class UnFillChannelJob extends BaseObject implements Job
{

    public $followeeId;
    public $channelId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $followingStoriesIds = ArrayHelper::getColumn(Story::findAll(['user_id' => $this->followeeId]), 'id');
        $channelStories = ChannelStory::findAll(['story_id' => $followingStoriesIds, 'channel_id' => $this->channelId]);

        foreach ($channelStories as $story) {
            $story->delete();
        }
    }
}