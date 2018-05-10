<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\redis\ValidatorLevel1;
use app\modules\v1\models\redis\ValidatorLevel2;
use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\Queue;

class DeleteValidationHumanCard extends BaseObject implements Job
{

    public $usr1Id;

    public $usr2Id;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $user1 = User::findOne($this->usr1Id);
        $user2 = User::findOne($this->usr2Id);

        $level1Node1 = ValidatorLevel1::findAll(['user_id' => $user1->getId()]);
        $level2Node1 = ValidatorLevel2::findAll(['user_id' => $user1->getId(), 'relationship_node' => $this->usr2Id]);

        $level1Node2 = ValidatorLevel1::findAll(['user_id' => $user2->getId()]);
        $level2Node2 = ValidatorLevel2::findAll(['user_id' => $user2->getId(), 'relationship_node' => $this->usr1Id]);


        // 1 solution
        /** @var ValidatorLevel1 $lev1 */
        foreach ($level1Node1 as $lev1) {
            if ($lev1->node_id == $user2->getId()) {
                $lev1->delete();
            }
        }

        /** @var ValidatorLevel1 $lev1 */
        foreach ($level1Node2 as $lev1) {
            if ($lev1->node_id == $user1->getId()) {
                $lev1->delete();
            }
        }

        // 2 solution
        /** @var ValidatorLevel2 $node */
        foreach ($level2Node1 as $node) {
            $node->delete();
        }

        /** @var ValidatorLevel2 $node */
        foreach ($level2Node2 as $node) {
            $node->delete();
        }

    }

}