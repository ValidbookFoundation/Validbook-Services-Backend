<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\redis\ChannelStory;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\story\StoryPermissionSettings;
use yii\base\BaseObject;
use yii\db\Query;
use yii\queue\Job;
use yii\queue\Queue;

class FillChannelBookJob extends BaseObject implements Job
{

    public $bookId;
    public $userId;
    public $channelId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $subQuery1 = (new Query())
            ->select('sb.story_id')
            ->from('story_book sb')
            ->innerJoin('follow_book fb', 'sb.book_id = fb.book_id')
            ->where(['sb.book_id' => $this->bookId]);

        $subQuery2 = (new Query())
            ->select('sps.story_id')
            ->from('story_permission_settings sps')
            ->leftJoin('story_custom_permissions scp', 'sps.custom_permission_id = scp.custom_id')
            ->where(['sps.permission_state' => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC])
            ->orWhere(['sps.permission_state' => StoryPermissionSettings::PRIVACY_TYPE_CUSTOM, 'scp.user_id' => $this->userId]);


        $storiesBooks = Story::find()->alias('s')
            ->innerJoin(['sbf' => $subQuery1], 's.id = sbf.story_id')
            ->innerJoin(['perm' => $subQuery2], 's.id = perm.story_id')
            ->where(['s.in_channels' => 1, 's.in_book' => 1])
            ->orderBy('s.created_at DESC')
            ->limit(300)
            ->all();


        foreach ($storiesBooks as $story) {
            if (!ChannelStory::find()->where(['channel_id' => $this->channelId, 'story_id' => $story->id])->exists()) {
                $modChannStory = new ChannelStory();
                $modChannStory->channel_id = $this->channelId;
                $modChannStory->story_id = $story->id;
                $modChannStory->story_created_at = $story->created_at;
                $modChannStory->is_blocked = false;

                $modChannStory->save();
            }
        }
    }
}