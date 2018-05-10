<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\book\Book;
use app\modules\v1\models\redis\ChannelStory;
use app\modules\v1\models\story\Story;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\queue\Job;
use yii\queue\Queue;

class UpdateBookForChannelJob extends BaseObject implements Job
{

    public $bookId;
    public $customPermissionId;
    public $permissionState;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $book = Book::findOne(['id' => $this->bookId]);

        $followers = (new Query())
            ->select('user_id, channel_id')
            ->from('follow')
            ->where(['followee_id' => $book->author_id])
            ->all();

        $followersBooks = (new Query())
            ->select('user_id, channel_id')
            ->from('follow_book')
            ->where(['book_id' => $book->id])
            ->all();

        $allFollowers = array_merge($followers, $followersBooks);
        $allFollowers = ArrayHelper::index($allFollowers, 'user_id');

        $cU = (new Query())
            ->from('book_custom_permissions')
            ->where(['custom_id' => $this->customPermissionId])
            ->all();
        $customUsers = ArrayHelper::getColumn($cU, 'user_id');


        foreach ($allFollowers as $fUser) {
            if (!empty($customUsers)) {
                if (!in_array($fUser['user_id'], $customUsers)) {
                    unset($allFollowers[$fUser['user_id']]);
                }
            }
            if ($fUser['user_id'] == Yii::$app->getUser()->getId()) {
                unset($allFollowers[$fUser['user_id']]);
            }
        }


        $followerChannels = ArrayHelper::getColumn($allFollowers, 'channel_id');

        $storiesBooks = Story::find()->alias('s')
            ->innerJoin('story_book sb', 's.id = sb.story_id')
            ->where(['s.in_channels' => 1, 's.in_book' => 1, 'sb.book_id' => $this->bookId])
            ->orderBy('s.created_at DESC')
            ->limit(300)
            ->all();

        foreach ($followerChannels as $channel) {
            foreach ($storiesBooks as $kk => $story) {
                $channelStory = ChannelStory::findOne(['story_id' => $story->id, 'channel_id' => $channel]);
                if ($this->permissionState == 1 or $this->permissionState == 2) {
                    if (empty($channelStory)) {
                        $modChannStory = new ChannelStory();
                        $modChannStory->channel_id = $channel;
                        $modChannStory->story_id = $story->id;
                        $modChannStory->story_created_at = $story->created_at;
                        $modChannStory->is_blocked = false;

                        $modChannStory->save();
                    } else {
                        $channelStory->is_blocked = false;
                        $channelStory->update();
                    }
                } elseif ($this->permissionState == 0) {
                    if (!empty($channelStory)) {
                        $channelStory->is_blocked = true;
                        $channelStory->update();
                    }
                }
            }
        }
    }
}