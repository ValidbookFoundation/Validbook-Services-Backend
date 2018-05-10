<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\jobs;

use app\modules\v1\models\redis\NodeValidator;
use app\modules\v1\models\redis\ValidatorLevel1;
use app\modules\v1\models\redis\ValidatorLevel2;
use app\modules\v1\models\User;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\queue\Job;
use yii\queue\Queue;

class AddSupporterHumanClaimCard extends BaseObject implements Job
{

    public $usr1Id;

    public $usr1_address;
    public $usr1_created;


    public $usr2Id;

    public $usr2_address;
    public $usr2_created;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $user1 = User::findOne($this->usr1Id);
        $user2 = User::findOne($this->usr2Id);
        $model1 = NodeValidator::findOne(['user_id' => $user1->getId()]);
        $model2 = NodeValidator::findOne(['user_id' => $user2->getId()]);

        if ($model1 == null) {
            $model1 = $this->setModel($user1, $this->usr1_address, $this->usr1_created);
        }
        if ($model2 == null) {
            $model2 = $this->setModel($user2, $this->usr2_address, $this->usr2_created);
        }

        $level1Node1 = ValidatorLevel1::findAll(['user_id' => $user1->getId()]);
        $level2Node1 = ValidatorLevel2::findAll(['user_id' => $user1->getId()]);
        $usr1_ids1 = ArrayHelper::getColumn($level1Node1, "node_id");
        $usr1_ids2 = ArrayHelper::getColumn($level2Node1, "node_id");

        $level1Node2 = ValidatorLevel1::findAll(['user_id' => $user2->getId()]);
        $level2Node2 = ValidatorLevel2::findAll(['user_id' => $user2->getId()]);
        $usr2_ids1 = ArrayHelper::getColumn($level1Node2, "node_id");
        $usr2_ids2 = ArrayHelper::getColumn($level2Node2, "node_id");


        // 1 solution
        /** @var ValidatorLevel2 $lev2 */
        foreach ($level2Node1 as $lev2) {
            if ($lev2->node_id == $user2->getId()) {
                $lev2->delete();
            }
        }

        /** @var ValidatorLevel2 $lev2 */
        foreach ($level2Node2 as  $lev2) {
            if ($lev2->node_id == $user1->getId()) {
                $lev2->delete();
            }
        }

        if (!in_array($user2->getId(), $usr1_ids1)) {
            $newModel = new ValidatorLevel1();
            $newModel->create($model1, $model2);
        }

        if (!in_array($user1->getId(), $usr2_ids1)) {
            $newModel = new ValidatorLevel1();
            $newModel->create($model2, $model1);
        }

        // 2 solution
        /** @var ValidatorLevel1 $node */
        foreach ($level1Node1 as $node) {
            if (!in_array($node->user_id, $usr2_ids1)) {
                if (!in_array($node->user_id, $usr2_ids2)) {
                    $newModel = new ValidatorLevel2();
                    $newModel->create($model1, $node, $model2->user_id);
                    $newModel2 = new ValidatorLevel2();
                    $newModel2->create($node, $model1, $model2->user_id);
                }
            }
        }

        /** @var ValidatorLevel1 $node */
        foreach ($level1Node2 as $node) {
            if (!in_array($node->user_id, $usr1_ids1)) {
                if (!in_array($node->user_id, $usr1_ids2)) {
                    $newModel = new ValidatorLevel2();
                    $newModel->create($model2, $node, $model1->user_id);
                    $newModel2 = new ValidatorLevel2();
                    $newModel2->create($node, $model2, $model1->user_id);
                }
            }
        }
    }


    private function setModel($user, $address, $created)
    {
        $model = new NodeValidator();
        $model->user_id = $user->getId();
        $model->first_name = $user->first_name;
        $model->last_name = $user->last_name;
        $model->slug = $user->slug;
        $model->avatar = $user->getAvatar('32x32', $user->getId());
        $model->created_at = $created;
        $model->public_address = $address;
        $model->save();

        return $model;
    }

}