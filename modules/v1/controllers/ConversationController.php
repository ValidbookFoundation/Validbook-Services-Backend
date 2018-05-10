<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\conversation\Conversation;
use app\modules\v1\models\conversation\ConversationToMessageUser;
use app\modules\v1\models\Message;
use app\modules\v1\models\TechMessage;
use app\modules\v1\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;


class ConversationController extends Controller
{
    public function actionIndex()
    {
        $page = Yii::$app->request->get('page', 1);
        $userId = \Yii::$app->getUser()->getId();

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $data = [];
        $modelConversation = new Conversation();
        $modelConversation->setPage($page);
        $modelsList = $modelConversation->getAllConversation();


        if (!empty($modelsList)) {
            /** @var Message $model */
            foreach ($modelsList as $model) {
                $receivers = Conversation::getAllUsersByConversation($model->conversation_id, $userId);

                $data[$model->conversation_id]['conversation_id'] = $model->conversation_id;
                $data[$model->conversation_id]['is_seen'] = $model->isSeenConversation($userId);
                $data[$model->conversation_id]['messages'][] = $model->getFormattedData();
                $data[$model->conversation_id]['receivers'] = $receivers;
                $data[$model->conversation_id]['delete_hours'] = Conversation::getDeleteHours($model->conversation_id);
            }
            $data = array_values($data);
        }
        return $this->success($data);
    }

    public function actionView($id)
    {
        $data = [];
        $page = Yii::$app->request->get('page', 1);
        $userId = \Yii::$app->getUser()->getId();

        $conversation = new Conversation();
        $conversation->setPage($page);
        $conversation->setItemsPerPage(30);

        $modelList = $conversation->getConversation($id);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        /** @var Message $model */
        foreach ($modelList as $model) {
            $data['conversation_id'] = (int)$model->conversation_id;
            $data['is_seen'] = $model->isSeenConversation($userId);
            $data['messages'][] = $model->getFormattedData();
            $data['receivers'] = Conversation::getAllUsersByConversation($model->conversation_id, $userId);
            $data['delete_hours'] = Conversation::getDeleteHours($model->conversation_id);
        }

        return $this->success($data);
    }

    public function actionConversationByUser()
    {
        $userIdsString = \Yii::$app->request->get('user_ids');
        $page = Yii::$app->request->get('page', 1);

        $result = [];

        $conversationId = Conversation::getConversationId($userIdsString);

        if (!empty($conversationId)) {
            $model = new Conversation();
            $model->setPage($page);
            $model->setItemsPerPage(30);

            $modelResult = $model->getConversation($conversationId);
            /** @var Message $model */
            foreach ($modelResult as $model) {
                $result['conversation_id'] = (int)$conversationId['conversation_id'];
                $result['messages'][] = $model->getFormattedData();
                $result['delete_hours'] = Conversation::getDeleteHours((int)$conversationId['conversation_id']);
            }
            if (!empty($conversationId) and empty($result)) {
                $data['conversation_id'] = (int)$conversationId['conversation_id'];
                $data['messages'] = [];
                $result['delete_hours'] = Conversation::getDeleteHours((int)$conversationId['conversation_id']);
                return $this->success($data);
            }
        }
        return $this->success($result);
    }

    public function actionDeleteConversation($id)
    {
        $result = Conversation::deleteConversation($id);

        if ($result) {
            return $this->success();
        }

        return $this->failure("Conversation not found");
    }

    public function actionLeftConversation($id)
    {
        $user = \Yii::$app->getUser()->identity;
        /** @var ConversationToMessageUser $conversation */
        $conversation = ConversationToMessageUser::find()
            ->where([
                'conversation_id' => $id,
                'user_id' => $user->getId(),
                'is_left' => 0,
                'is_deleted' => 0
            ])
            ->one();

        if (empty($conversation)) {
            return $this->failure('Conversation does\'nt exists');
        }


        $conversationInfo = Conversation::findOne($conversation->conversation_id);
        if (!empty($conversationInfo)) {

            $conversationInfo->is_group = 1;
            $conversationInfo->count_users -= 1;
            $conversationInfo->update();

            if ($conversationInfo->is_group != 1) {
                return $this->failure("You can't left private conversation", 422);
            }
        } else {
            return $this->failure('Conversation does not exists', 422);
        }


        Conversation::leftConversation($conversation->conversation_id);

        $receiversArray = (new Query())
            ->select('cm.user_id')
            ->from('conversation_to_message_user cm')
            ->where(['cm.conversation_id' => $id, 'is_left' => 0, 'is_deleted' => 0])
            ->all();

        $receivers = ArrayHelper::getColumn($receiversArray, 'user_id', true);

        $text = $user->getFullName() . ' left the conversation';

        $message = new Message([
            'user_id' => $user->getId(),
            'text' => $text,
            'conversation_id' => $id,
            'receivers' => $receivers,
            'is_new' => 1,
            'is_tech' => 1
        ]);

        if ($message->validate()) {

            $message->save();

            return $this->success();
        }
    }

    public function actionMarkRead($id)
    {

        /** @var \app\modules\v1\models\conversation\ConversationToMessageUser $model */
        $model = ConversationToMessageUser::find()
            ->where(["conversation_id" => $id,
                'user_id' => Yii::$app->user->id,
                'is_left' => 0, 'is_deleted' => 0])
            ->one();

        if (!empty($model)) {
            $model->is_seen = 1;
            $model->update();

            return $this->success();
        } else {
            return $this->failure("Conversation does not exists");
        }
    }

    public function actionMarkReadAll()
    {
        $conversation = ConversationToMessageUser::find()
            ->where([
                "user_id" => Yii::$app->getUser()->id,
                "is_seen" => 0,
                'is_left' => 0,
                'is_deleted' => 0
            ])->all();

        if ($conversation) {
            /** @var ConversationToMessageUser $model */
            foreach ($conversation as $model) {
                $model->is_seen = 1;
                $model->update();
            }
        }

        return $this->success();
    }

    public function actionMarkSeenAll()
    {
        $conversation = ConversationToMessageUser::find()
            ->where([
                "user_id" => Yii::$app->getUser()->id,
                "is_new" => 1
            ])->all();

        if ($conversation) {
            /** @var ConversationToMessageUser $model */
            foreach ($conversation as $model) {
                $model->is_new = 0;
                $model->update();
            }
        }

        return $this->success();
    }

    public function actionAddMemberToGroup($id)
    {
        $guestIds = Yii::$app->request->post('user_id', []);

        $user = Yii::$app->getUser()->identity;

        $guests = User::findAll(['id' => $guestIds, 'status' => User::STATUS_ACTIVE]);

        $result = [];

        foreach ($guests as $guest) {
            $conversation = Conversation::addMemberConversation($id, $guest->id);
        }

        if (empty($conversation)) {
            return $this->failure('Conversation does not exists', 422);
        } else {

            $receiversArray = $conversation->receivers;
            foreach ($receiversArray as $item) {
                if ($item['is_left'] == 0 && in_array($item['user_id'], $guestIds)) {
                    return $this->failure('User(s) already in conversation', 422);
                }
            }
            $receiversIds = ArrayHelper::getColumn($receiversArray, 'user_id');

            if (!empty($receiversArray)) {
                /** @var User $guestId */
                foreach ($guests as $guest) {
                    $text = $user->getFullName() . ' added ' . $guest->getFullName();
                    $message = new Message([
                        'user_id' => $guest->id,
                        'text' => $text,
                        'conversation_id' => $conversation->id,
                        'receivers' => $receiversIds,
                        'is_new' => 1,
                        'is_tech' => 1
                    ]);
                    if ($message->validate()) {

                        $message->save();
                    }
                    $result[] = $message->getFormattedData();
                }
                return $this->success($result, 201);
            }
        }
    }

    public function actionChangeHours($id)
    {
        $hours = Yii::$app->request->post('delete_hours');

        if (!is_numeric($hours)) {
            return $this->failure("Invalid parameter 'hours'", 400);
        }

        if ($hours < 0 || $hours > 26280) {
            return $this->failure("Invalid parameter 'hours'", 400);
        }

        $conversation = Conversation::findOne($id);

        if ($conversation === null) {
            return $this->failure("Conversation does not exist");
        }

        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        $checkUser = ConversationToMessageUser::findOne([
            'user_id' => $user->getId(),
            'conversation_id' => $conversation->id,
            'is_left' => 0
        ]);

        if ($checkUser === null) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $message = $conversation->setDeleteHoursMessage($hours, $user);

        if (array_key_exists('text', $message)) {

            return $this->success($message, 201);
        } else {
            return $this->failure($message, 422);
        }
    }

}