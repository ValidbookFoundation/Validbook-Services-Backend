<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\Message;
use app\modules\v1\models\MessageToReceiver;
use app\modules\v1\models\OldMessage;
use Yii;
use yii\db\Query;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
    /**
     * Creates a new Message model
     * @return mixed
     */
    public function actionCreate()
    {
        $text = Yii::$app->request->post('text');
        $conversationId = Yii::$app->request->post('conversation_id');
        $receivers = Yii::$app->request->post('receivers');

        if (count($receivers) > 150) {
            return $this->failure('You can\'t open conversation over 150 people', 422);
        }

        if (empty($receivers)) {
            return $this->failure('Receivers can\'t be empty', 422);
        }

        $model = new Message([
            'user_id' => Yii::$app->user->getId(),
            'text' => $text,
            'conversation_id' => $conversationId,
            'receivers' => $receivers,
            'is_new' => 1,
            'is_tech' => 0
        ]);

        if ($model->validate()) {

            $model->save();

            return $this->success($model->getFormattedData(), 201);
        } else {
            return $this->failure($model->errors, 422);
        }
    }

    /**
     * Deletes an existing Message model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $messageId = (new Query())
            ->select('t2.id')
            ->from('message t1')
            ->leftJoin('message_to_receiver t2', 't1.id = t2.message_id')
            ->where(['t1.id' => $id, 't2.user_id' => Yii::$app->user->identity->getId()])
            ->one();

        if ($messageId !== null) {
            $model = MessageToReceiver::findOne($messageId);
            if ($model->is_deleted == 0) {
                $model->is_deleted = 1;
                $model->save();
                return $this->success();
            }
        }
        return $this->failure("Message does not exists");
    }
}
