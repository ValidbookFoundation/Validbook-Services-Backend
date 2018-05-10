<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;


use app\modules\v1\models\following\Follow;
use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\redis\ChannelStory;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\queue\Job;
use yii\queue\Queue;

class UnFillStoryInChannelsAfterLogJob extends BaseObject implements Job
{

    public $userId;
    public $bookId;
    public $storyId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        //fill ChannelStory table

        $book = (new Query())
            ->select('id, permission_state, custom_permission_id')
            ->from('book_permission_settings')
            ->where(['book_id' => $this->bookId, 'permission_id' => 2])
            ->one();


        if ($book['permission_state'] == 1) {
            $followers = Follow::findAll([
                'followee_id' => $this->userId,
                'is_follow' => 1,
                'is_block' => 0
            ]);
        } elseif ($book['permission_state'] == 2 and $book['custom_permission_id'] != null) {
            $customUserIds = ArrayHelper::getColumn((new Query())
                ->select('user_id')
                ->from('book_custom_permissions')
                ->where(['custom_id' => $book['custom_permission_id']])
                ->all(),
                'user_id');

            $followers = Follow::find()
                ->where([
                    'followee_id' => $this->userId,
                    'is_follow' => 1,
                    'is_block' => 0,
                    'user_id' => $customUserIds
                ])
                ->all();
        } else {
            $followers = Follow::find()
                ->where([
                    'followee_id' => $this->userId,
                    'is_follow' => 1,
                    'is_block' => 0,
                    'user_id' => $this->userId
                ])
                ->all();
        }

        $followerBooks = FollowBook::findAll([
            'book_id' => $this->bookId,
            'is_follow' => 1,
            'is_block' => 0
        ]);

        $channelsFollowers = ArrayHelper::getColumn($followers, 'channel_id');
        $channelsFbooks = ArrayHelper::getColumn($followerBooks, 'channel_id');

        $channels = array_unique(array_merge($channelsFollowers, $channelsFbooks));

        foreach ($channels as $id) {

            $channelStory = ChannelStory::find()
                ->where(['channel_id' => $id, 'story_id' => $this->storyId])
                ->one();

            if (!empty($channelStory)) {
                $channelStory->delete();
            }
        }
    }
}