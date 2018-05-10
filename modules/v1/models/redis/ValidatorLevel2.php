<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;


use yii\redis\ActiveRecord;

class ValidatorLevel2 extends ActiveRecord
{
    public function attributes()
    {
        return [
            "id",
            "user_id",
            "node_id",
            "relationship_node",
            "first_name",
            "last_name",
            "slug",
            "avatar",
            "created_at",
            "public_address"
        ];
    }

    public function create($usr, $node, $relation_id)
    {
        $this->user_id = $usr->user_id;
        $this->node_id = $node->user_id;
        $this->relationship_node = $relation_id;
        $this->first_name = $node->first_name;
        $this->last_name = $node->last_name;
        $this->created_at = $node->created_at;
        $this->slug = $node->slug;
        $this->avatar = $node->avatar;
        $this->public_address = $node->public_address;

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