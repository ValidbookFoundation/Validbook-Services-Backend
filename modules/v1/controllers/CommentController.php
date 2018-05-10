<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\Comment;
use app\modules\v1\models\notification\CommentReceiver;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\User;
use Yii;


class CommentController extends Controller
{
    public function actionView($id)
    {
        $model = Comment::findOne($id);

        if (!empty($model)) {
            return $this->success($model->getFormattedData());
        }

        return $this->failure("Comment doesn't exist");
    }

    public function actionStory()
    {
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $entityId = Yii::$app->request->get('entity_id');


        $model = new Comment();
        $modelsList = $model->getCommentsForStory($entityId, $page, 'story');

        if (!empty($modelsList)) {
            return $this->success($modelsList);
        }

        return $this->success();
    }

    /**
     * Creates a new Comment model.
     * @return mixed
     */
    public function actionCreate()
    {
        $content = (string)Yii::$app->request->post('content');
        $parentId = Yii::$app->request->post('parent_id', 0);
        $entity = Yii::$app->request->post('entity', 'story');
        $entityId = Yii::$app->request->post('entity_id');
        $createdBy = Yii::$app->request->post('created_by');
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;

        if ($content == null) {
            return $this->failure('Comment cannot be empty', 422);
        }

        if (empty($entityId)) {
            return $this->failure('Entity id cannot be empty', 422);
        }

        if ($modelUser->id != $createdBy) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        switch ($entity) {
            case $entity == Comment::DEFAULT_ENTITY:
                $entityModel = Story::findOne($entityId);
                //check if story is visible and open for this user who create a comment
                if ($entityModel === null) {
                    return $this->failure("Entity does not exists");
                }
                if (!$entityModel->isVisibleForUser()) {
                    return $this->failure("Entity does not exists");
                }
                break;
        }

        $model = new Comment([
            'entity' => $entity,
            'entity_id' => $entityId,
            'content' => $content,
            'parent_id' => $parentId,
            'created_by' => $createdBy
        ]);

        if ($model->validate()) {
            $model->save();


            // send notification
            $receivers = [];
            $notBuilder = new NotificationFactory(
                $modelUser,
                $receivers,
                $entityModel
            );
            $commentReceiver = new CommentReceiver();
            $commentReceiver->entity_id = $entityId;
            $commentReceiver->parent_id = $parentId;
            $commentReceiver->identityUser = $modelUser;

            $users = [];
            $receivers = $commentReceiver->getReceiver($users);
            $receivers = $notBuilder->filterReceivers($receivers);
            $notBuilder->addModel($receivers);
            $notBuilder->build();

            return $this->success($model->getDataForNewComment(), 201);
        } else {
            return $this->failure($model->errors, 422);
        }
    }

    /**
     * Updates an existing Comment model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!$this->hasOwnerAccessRights(Comment::className(), 'created_by', $id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $content = Yii::$app->request->post('content', '');


        $model = Comment::findOne($id);

        if ($model !== null) {
            $model->content = $content;

            if ($model->validate()) {
                $model->update();

                $data = $model->getFormattedData();

                return $this->success($data);
            } else {
                return $this->failure($model->errors);
            }
        } else {
            return $this->failure("Comment does not exists");
        }
    }

    /**
     * Deletes an existing Comment model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->hasOwnerAccessRights(Comment::className(), 'created_by', $id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model = Comment::findOne($id);

        if ($model !== null) {
            $model->delete();
            return $this->success();
        } else {
            return $this->failure("Comment does not exists");
        }
    }
}
