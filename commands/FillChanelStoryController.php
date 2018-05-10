<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use app\modules\v1\models\redis\ChannelStory;
use app\modules\v1\models\User;
use yii\console\Controller;
use yii\db\Query;

class FillChanelStoryController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->all();

        /** @var User $user */
        foreach ($users as $user) {
            $stories = (new Query())
                ->select('s.id, min(sb.created_at) min_date')
                ->from('story s')
                ->innerJoin('story_book sb', 's.id = sb.story_id')
                ->where(['s.user_id' => $user->getId(), 's.in_channels' => 1, 'sb.is_moved_to_bin' => 0])
                ->groupBy('s.id')
                ->orderBy('min_date DESC')
                ->limit(300)
                ->all();

            foreach ($stories as $story) {

                $modChannStory = new ChannelStory();
                $modChannStory->channel_id = $user->getDefaultChannelId();
                $modChannStory->story_id = $story['id'];
                $modChannStory->story_created_at = $story['min_date'];
                $modChannStory->is_blocked = false;

                $modChannStory->save();
            }
        }
    }
}