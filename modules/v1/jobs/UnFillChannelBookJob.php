<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\redis\ChannelStory;
use app\modules\v1\models\story\StoryBook;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\queue\Job;
use yii\queue\Queue;

class UnFillChannelBookJob extends BaseObject implements Job
{
    public $bookId;
    public $channelId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $followingBooksIds = ArrayHelper::getColumn(StoryBook::findAll(['book_id' => $this->bookId]), 'story_id');
        $channelStories = ChannelStory::findAll(['story_id' => $followingBooksIds, 'channel_id' => $this->channelId]);

        foreach ($channelStories as $story) {
            $story->delete();
        }
    }
}