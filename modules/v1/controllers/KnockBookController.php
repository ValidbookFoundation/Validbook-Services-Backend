<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */


namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\book\BookCustomPermissions;
use app\modules\v1\models\book\BookPermissionSettings;
use app\modules\v1\models\book\KnockBook;
use app\modules\v1\models\notification\KnockBookReceiver;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\User;
use Yii;
use yii\helpers\ArrayHelper;


class KnockBookController extends Controller
{
    public function actionKnockOnBook($id)
    {
        $bookAuthorId = Yii::$app->request->post('book_author_id');
        $knockUserId = Yii::$app->request->post('knock_user_id');

        if ($bookAuthorId == $knockUserId) {
            return $this->failure("You can't knocking on your book", 422);
        }

        $book = BookPermissionSettings::findOne(['book_id' => $id, 'permission_id' => 2]);
        if ($book->permission_state == 1) {
            return $this->failure("You can't knocking on public book", 422);
        }
        if ($book->permission_state == 2) {
            $customPermissions = BookCustomPermissions::findAll(['custom_id' => $book->custom_permission_id]);
            $customUserIds = ArrayHelper::getColumn($customPermissions, 'user_id');
            if (in_array($knockUserId, $customUserIds)) {
                return $this->failure("You can't knocking on book that you see content", 422);
            }
        }

        $model = new KnockBook([
            'book_id' => $id,
            'book_author_id' => $bookAuthorId,
            'user_id' => $knockUserId
        ]);

        if ($model->validate()) {
            $model->save();

           //send notification
            $sender = User::findOne($model->user_id);
            $bookModel = Book::findOne($model->book_id);
            $notBuilder = new NotificationFactory($sender, $model->book_author_id, $bookModel);
            $likeReceiver = new KnockBookReceiver();
            $receivers = $likeReceiver->getReceiver($model->book_author_id);
            $receivers = $notBuilder->filterReceivers($receivers);
            $notBuilder->addModel($receivers);
            $notBuilder->build();

            return $this->success(['status' => 'Pending']);
        } else {
            return $this->failure($model->errors);
        }
    }

    public function actionIndex()
    {
        $userId = Yii::$app->getUser()->identity->getId();
        $model = new KnockBook();
        $data = $model->getListBooks($userId);
        return $this->success($data);
    }

    public function actionView($id)
    {
        $userId = Yii::$app->getUser()->identity->getId();
        $model = new KnockBook();
        $data = $model->getKnockBook($userId, $id);
        return $this->success($data);
    }

    public function actionIgnore($knockId)
    {
        $model = KnockBook::findOne($knockId);

        if (!$this->hasOwnerAccessRights(KnockBook::className(), 'book_author_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (empty($model)) {
            return $this->failure("Model doesn't exists");
        }
        if (!$model->delete()) {
            return $this->failure("Internal error", 500);
        }
        return $this->success();
    }

    public function actionSubmit($knockId)
    {
        /** @var KnockBook $model */
        $model = KnockBook::findOne($knockId);

        if (empty($model)) {
            return $this->failure("Model does'nt exists");
        }

        if (!$this->hasOwnerAccessRights(KnockBook::className(), 'book_author_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (!$model->submitKnock()) {
            return $this->failure("Internal error", 500);
        }

        return $this->success();
    }

}