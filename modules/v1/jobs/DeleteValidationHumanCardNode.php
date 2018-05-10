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
use yii\queue\Job;
use yii\queue\Queue;

class DeleteValidationHumanCardNode extends BaseObject implements Job
{

    public $usrId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $user = User::findOne($this->usrId);

        $model = NodeValidator::findOne(['user_id' => $user->getId()]);

        if (!empty($model)) {
            ValidatorLevel1::deleteAll(['user_id' => $user->getId()]);
            ValidatorLevel1::deleteAll(['node_id' => $user->getId()]);
            ValidatorLevel2::deleteAll(['user_id' => $user->getId()]);
            ValidatorLevel2::deleteAll(['node_id' => $user->getId()]);
            ValidatorLevel2::deleteAll(['relationship_node' => $user->getId()]);

            $model->delete();
        }
    }

}