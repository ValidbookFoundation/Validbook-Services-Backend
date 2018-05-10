<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use app\modules\v1\models\Channel;
use app\modules\v1\models\following\Follow;
use yii\console\Controller;
use yii\db\Query;

class DefaultFollowerController extends Controller
{

    public function actionIndex()
    {
        $users = (new Query())->from('user')->all();

        foreach ($users as $user) {

            $defaultChannel  = Channel::find()->where(['is_default' => 1, 'user_id' => $user['id']])->one()->id;
            $followModel = new Follow();
            $followModel->user_id = $user['id'];
            $followModel->channel_id = $defaultChannel;
            $followModel->followee_id = $user['id'];
            $followModel->is_follow = 1;
            $followModel->is_block = 0;
            $followModel->save();
        }
    }
}