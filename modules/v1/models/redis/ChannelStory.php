<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;

use yii\redis\ActiveRecord;

class ChannelStory extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'channel_id', 'story_id', 'story_created_at', 'is_blocked'];
    }
}