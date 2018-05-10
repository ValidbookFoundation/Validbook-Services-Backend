<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\Like;
use app\modules\v1\models\notification\LikeReceiver;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\story\Story;
use Yii;

class LikeController extends Controller
{
    public function actionStory()
    {
        $storyId = Yii::$app->request->post('story_id');
        $user = Yii::$app->user->identity;

        $model = Like::find()->where(['sender_id' => $user->getId(), 'story_id' => $storyId])->one();
        $story = Story::find()->where(['id' => $storyId])->one();

        if (!empty($model)) {
            //like story
            $model = new Like([
                'sender_id' => $user->getId(),
                'story_id' => $storyId,
                'object_id' => $storyId,
                'model' => Story::tableName()
            ]);

            if (empty($story)) {
                return $this->failure("Story does not exists");
            }

            if ($model->validate()) {
                $model->save();
                $likes = Like::getStoryLikes($story);

                //send notification
                $notBuilder = new NotificationFactory($user, $model->story->user_id, $story);
                $likeReceiver = new LikeReceiver();
                $receivers = $likeReceiver->getReceiver($model->story->user_id);
                $receivers = $notBuilder->filterReceivers($receivers);
                $notBuilder->addModel($receivers);
                $notBuilder->build();

                $response = [
                    "likes" => $likes
                ];

                return $this->success($response, 201);
            }
        } else {
            //dislike story
            $model->delete();
            $likes = Like::getStoryLikes($story);

            $response = [
                "likes" => $likes
            ];

            return $this->success($response, 201);
        }
    }

    public function actionPhoto()
    {
        $photoId = Yii::$app->request->post('photo_id');
        $userId = Yii::$app->user->id;

        $model = Like::find()->where(['sender_id' => $userId, 'photo_id' => $photoId])->one();

        if (!empty($model)) {
            //like photo
            $model = new Like([
                'sender_id' => $userId,
                'photo_id' => $photoId,
                'object_id' => $photoId,
                'model' => Photo::tableName()
            ]);

            if (!Photo::find()->where(['id' => $photoId])->exists()) {
                return $this->failure("Photo does not exists");
            }

            //check if photo is visible for this user
            if ($model->photo->isVisibleForUser())
                $this->failure("You cannot like this photo", 401);

            if ($model->validate()) {
                $model->save();
                $likes = Like::getPhotoLikes($photoId);

                return $this->success($likes, 201);
            }
        } else {
            //dislike photo
            $model->delete();
            $likes = Like::getPhotoLikes($photoId);

            return $this->success($likes, 201);
        }
    }
}