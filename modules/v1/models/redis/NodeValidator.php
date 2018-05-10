<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\redis;


use yii\helpers\ArrayHelper;
use yii\redis\ActiveRecord;

/**
 * Class NodeValidator
 * @package app\modules\v1\models\redis
 */
class NodeValidator extends ActiveRecord
{
    public function attributes()
    {
        return [
            "id",
            "user_id",
            "first_name",
            "last_name",
            "slug",
            "avatar",
            "created_at",
            "public_address"
        ];
    }

    public function getFormattedCard()
    {
        $res = [
            'user_id' => (int)$this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'created_at' => $this->created_at,
            'slug' => $this->slug,
            'avatar' => $this->avatar,
            'public_address' => $this->public_address,
            'level_1' => $this->getLevel1(),
            'level_2' => $this->getLevel2()
        ];

        return $res;

    }


    private function getLevel1()
    {
        $res = [];

        $models = ValidatorLevel1::findAll(['user_id' => $this->user_id]);

        if (!empty($models)) {
            /** @var ValidatorLevel1 $model */
            foreach ($models as $model) {
                $res[] = $model->getFormattedData();
            }
        }

        return $res;
    }

    private function getLevel2()
    {
        $res = [];

        $models = ValidatorLevel2::findAll(['user_id' => $this->user_id]);

        if (!empty($models)) {
            foreach ($models as $model) {
                $res[$model->user_id] = $model->getFormattedData();
            }
            $res = array_merge([], $res);
        }

        return $res;
    }

    public function getLevels(self $node)
    {
        $level1Node = ValidatorLevel1::findAll(['user_id' => $node->user_id]);
        $level1NodeIds = ArrayHelper::getColumn($level1Node, "node_id");
        $mainNodeL1 = ValidatorLevel1::findAll(['user_id' => $this->user_id]);
        $mainNodeL1Ids = ArrayHelper::getColumn($mainNodeL1, "node_id");
        $mainNodeL2 = ValidatorLevel2::findAll(['user_id' => $this->user_id]);
        $mainNodeL2Ids = ArrayHelper::getColumn($mainNodeL2, "node_id");

        $res["node_id"] = (int)$node->user_id;
        $res["level_1"] = array_intersect($mainNodeL1Ids, $level1NodeIds);
        $res["level_2"] = array_intersect($mainNodeL2Ids, $level1NodeIds);

        $dataForCheck = array_merge($mainNodeL1Ids, $mainNodeL2Ids);

        if (($k = array_search($node->user_id, $dataForCheck)) !== FALSE){
            unset($dataForCheck[$k]);
        }

        if (($k = array_search($this->user_id, $level1NodeIds)) !== FALSE){
            unset($level1NodeIds[$k]);
        }

        $res["level_3"] = [];
        $dataForL3 = array_diff($level1NodeIds, $dataForCheck);

        $mainNodeL3 = ValidatorLevel1::findAll(['user_id' => $node->user_id, 'node_id' => $dataForL3]);
        /** @var ValidatorLevel1 $item */
        foreach ($mainNodeL3 as $item) {
            $res["level_3"][] = $item->getFormattedData();
        }


        return $res;
    }

}