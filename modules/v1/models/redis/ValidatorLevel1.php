<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;

use yii\redis\ActiveRecord;

class ValidatorLevel1 extends ActiveRecord
{
    public function attributes()
    {
        return [
            'id',
            "user_id",
            "node_id",
            "first_name",
            "last_name",
            "slug",
            "avatar",
            "created_at",
            "public_address"
        ];
    }

    public function create($usr1, $usr2)
    {
        $this->user_id = $usr1->user_id;
        $this->node_id = $usr2->user_id;
        $this->first_name = $usr2->first_name;
        $this->last_name = $usr2->last_name;
        $this->created_at = $usr2->created_at;
        $this->slug = $usr2->slug;
        $this->avatar = $usr2->avatar;
        $this->public_address = $usr2->public_address;

        $this->save();
    }

    public function getFormattedData()
    {
        return [
            'node_id' => (int)$this->node_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'created_at' => $this->created_at,
            'slug' => $this->slug,
            'avatar' => $this->avatar,
            'public_address' => $this->public_address,
        ];
    }


}