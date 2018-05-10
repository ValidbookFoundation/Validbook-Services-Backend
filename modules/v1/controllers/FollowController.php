<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\following\Follow;
use app\modules\v1\models\following\FollowBook;
use app\modules\v1\models\following\WhoToFollow;
use app\modules\v1\models\notification\FollowBookReceiver;
use app\modules\v1\models\notification\FollowReceiver;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\User;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * Class FollowController
 * @package app\modules\v1\controllers
 *
 *  * @property User $sender
 */
class FollowController extends Controller
{
    public function actionSimpleFollowUser()
    {
        $followedUserId = Yii::$app->request->post('user_id');

        if ($followedUserId == null) {
            return $this->failure('user_id cannot be empty', 422);
        }
        /** @var User $modelUser */
        $modelUser = Yii::$app->getUser()->identity;
        $channelId = $modelUser->getDefaultChannelId();

        $followModel = Follow::find()
            ->where([
                'user_id' => $modelUser->id,
                'channel_id' => $channelId,
                'followee_id' => $followedUserId
            ])
            ->one();

        if (!empty($followModel)) {
            return $this->failure('User has already followed this user in this channel', 422);
        }

        $model = new Follow([
            'user_id' => $modelUser->id,
            'channel_id' => $channelId,
            'followee_id' => $followedUserId,
            'is_follow' => 1,
            'is_block' => 0
        ]);

        if ($model->validate()) {
            $model->save();
        } else {
            return $this->failure($model->errors);
        }

        // send notification
        $notBuilder = new NotificationFactory($modelUser, $model->followee_id, $modelUser);
        $followReceiver = new FollowReceiver();
        $receivers = $followReceiver->getReceiver($model->followee_id);
        $receivers = $notBuilder->filterReceivers($receivers);
        $notBuilder->addModel($receivers);
        $notBuilder->build();


        return $this->success(['is_follow' => true]);
    }

    public function actionSimpleUnfollowUser()
    {
        $followedUserId = Yii::$app->request->post('user_id');
        /** @var User $modelUser */
        $modelUserId = Yii::$app->getUser()->identity->getId();

        $data = Follow::unFollowAllForUser($followedUserId, $modelUserId);


        return $this->success($data);
    }

    public function actionFollowDiff()
    {
        $channels = Yii::$app->request->post('user_channels', []);
        $followedUserId = Yii::$app->request->post('user_id');
        $books = Yii::$app->request->post('books_channels', []);

        if ($followedUserId == null) {
            return $this->failure('user_id cannot not be empty', 400);
        }

        /** @var User $modelUser */
        $modelUser = Yii::$app->getUser()->identity;

        $resultChannels = Follow::updateChannels($followedUserId, $modelUser->getId(), $channels);
        $resultBooks = Follow::updateBooks($followedUserId, $modelUser->getId(), $books);

        if (empty($resultChannels) and empty($resultBooks)) {
            return $this->success(Follow::getFormattedDataForPopup($followedUserId), 201);
        }

        return $this->failure(array_merge($resultChannels, $resultBooks));
    }


    public function actionSimpleFollowBook()
    {
        $bookId = Yii::$app->request->post('book_id');
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;
        //check existence and access to book
        /** @var Book $modelBook */
        $modelBook = Book::find()->where(["id" => $bookId])->one();
        if (empty($modelBook)) {
            return $this->failure("Book does not exists");
        }

        if (!$modelBook->isExistenceVisibile() or !$modelBook->isContentVisible()) {
            return $this->failure("You have not access to follow this book", 401);

        }
        $booksIds = ArrayHelper::getColumn($modelBook->children(1)->all(), 'id');
        $booksIds[] = $bookId;
        $channelId = $modelUser->getDefaultChannelId();

        $followBooks = FollowBook::findAll(['user_id' => $modelUser->getId(), 'book_id' => $booksIds]);

        if (!empty($followBooks)) {
            return $this->failure('User has already followed this book in this channel', 422);
        }

        foreach ($booksIds as $id) {
            $model = new FollowBook();
            $model->user_id = $modelUser->getId();
            $model->book_id = $id;
            $model->channel_id = $channelId;
            $model->is_follow = 1;
            $model->is_block = 0;
            if ($model->validate()) {
                $model->save();
            } else {
                return $this->failure($model->errors);
            }
        }

        // send notification
        $notBuilder = new NotificationFactory($modelUser, $modelBook->author_id, $modelBook);
        $followBookReceiver = new FollowBookReceiver();
        $receivers = $followBookReceiver->getReceiver($modelBook->author_id);
        $receivers = $notBuilder->filterReceivers($receivers);
        $notBuilder->addModel($receivers);
        $notBuilder->build();


        return $this->success(['is_follow' => true]);
    }

    public function actionSimpleUnfollowBook()
    {
        $bookId = Yii::$app->request->post('book_id');
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;

        $modelBook = Book::find()->where(["id" => $bookId])->one();
        if (empty($modelBook)) {
            return $this->failure("Book does not exists");
        }

        $booksIds = ArrayHelper::getColumn($modelBook->children(1)->all(), 'id') + [$bookId];

        $followBooks = FollowBook::findAll(['user_id' => $modelUser->getId(), 'book_id' => $booksIds]);

        foreach ($followBooks as $followBook) {
            $followBook->delete();
        }

        return $this->success(['is_follow' => false]);
    }


    public function actionFollowDiffBook()
    {
        $books = Yii::$app->request->post('books_channels', []);

        $bookIds = ArrayHelper::getColumn($books, 'book_id');

        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;

        $resultBooks = Follow::updateBook($modelUser->getId(), $books);

        if (empty($resultBooks)) {
            return $this->success(Follow::getFormattedDataForBookPopup($bookIds), 201);
        }

        return $this->failure($resultBooks);

    }

    public function actionWhoToFollow()
    {
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;

        $users = new WhoToFollow();
        $userIds = $users->getUserIds();

        return $this->success($modelUser->whoToFollow($userIds));

    }

    public function actionGetFollowPopup($userId)
    {
        $data = Follow::getFormattedDataForPopup($userId);

        return $this->success($data);
    }

    public function actionGetFollowBookPopup($bookId)
    {
        $data = Follow::getFormattedDataForBookPopup($bookId);

        return $this->success($data);
    }

}