<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\Channel;
use app\modules\v1\models\following\Follow;
use app\modules\v1\models\User;
use Yii;

class ChannelController extends Controller
{
    public function actionIndex()
    {
        $data = [];
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;
        $modelList = Channel::find()
            ->where(['user_id' => $modelUser->getId()])
            ->all();

        /** @var Channel $model */
        foreach ($modelList as $model) {
            $data[] = [
                "id" => $model->id,
                "name" => $model->name,
                "slug" => $model->slug,
                "description" => $model->description,
            ];
        }
        return $this->success($data);
    }

    public function actionCreate()
    {
        $name = Yii::$app->request->post('name');
        $description = Yii::$app->request->post('description');
        $content = Yii::$app->request->post('content');
        $modelUser = Yii::$app->getUser()->identity;

        $responseContent = [];


        $model = new Channel([
            'name' => $name,
            'description' => $description,
            'user_id' => Yii::$app->user->id
        ]);

        if ($model->validate()) {
            $model->save();

            if (!empty($content['added'])) {
                $responseContent = $model->addedContent($modelUser, $content);
            }

            $responseData = [
                'id' => $model->id,
                'name' => $model->name,
                'slug' => $model->slug,
            ];

            return $this->success(array_merge($responseData, $responseContent), 201);
        } else {
            return $this->failure($model->errors);
        }
    }

    public function actionUpdate($id)
    {
        if (!$this->hasOwnerAccessRights(Channel::className(), 'user_id', $id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $name = Yii::$app->request->post('name');
        $description = Yii::$app->request->post('description');
        $content = Yii::$app->request->post('content');
        $modelUser = Yii::$app->getUser()->identity;

        $responseContent = [];

        /** @var Channel $model */
        $model = Channel::findOne($id);

        if (!empty($model)) {
            if (!empty($name)) {
                $model->name = $name;
            }

            if (!empty($description)) {
                $model->description = $description;
            }

            if ($model->validate()) {
                $model->update();

                if (!empty($content['added'])) {
                    $responseContent = $model->addedContent($modelUser, $content['added']);
                }

                if (!empty($content['removed'])) {
                    $model->removedContent($modelUser, $content['removed']);
                }

                $responseData = [
                    'id' => $model->id,
                    'name' => $model->name
                ];

                return $this->success(array_merge($responseData, $responseContent));
            } else {

                return $this->failure($model->errors);
            }
        }
    }

    public function actionDelete($id)
    {
        if (!$this->hasOwnerAccessRights(Channel::className(), 'user_id', $id))
            return $this->failure("You are not allowed to perform this action", 401);

        $channel = Channel::find()->where(['id' => $id])->one();
        if (!empty($channel)) {
            if ($channel->delete()) {
                return $this->success();
            }
        } else {
            return $this->failure("Channel doesn't exists");
        }
    }

    public function actionView($channel_slug = 'mashup')
    {
        $channelSlug = $channel_slug;
        $page = Yii::$app->request->get('page', 1);
        $userId = Yii::$app->user->getId();

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        /** @var Channel $model */
        $model = Channel::find()->where([
            'slug' => $channelSlug,
            'user_id' => $userId
        ])->one();

        $model->setPage($page);
        $model->setItemsPerPage(10);

        if ($model !== null) {
            $data = [
                "id" => $model->id,
                "name" => $model->name,
                "slug" => $model->slug,
                "description" => $model->description,
                "stories" => $model->getChannelStories()
            ];

            return $this->success($data);
        } else
            return $this->failure("Channel doesn't exist");
    }

    public function actionFollowingList($channelId)
    {
        $modelUser = Yii::$app->getUser();
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }
        $follow = new Follow();
        $follow->setPage($page);
        $follow->setItemsPerPage(10);
        $result = $follow->userFollowingForChannel($modelUser->getId(), $channelId);
        return $this->success($result);
    }

    public function actionFollowingBooks($channelId)
    {
        $modelUser = Yii::$app->getUser();
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        $follow = new Follow();
        $follow->setPage($page);
        $follow->setItemsPerPage(10);

        $result = $follow->booksForChannel($modelUser->getId(), $channelId);

        return $this->success($result);
    }

}