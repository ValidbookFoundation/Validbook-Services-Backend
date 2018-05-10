<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\box\Box;
use app\modules\v1\models\box\BoxPermissionSettings;
use app\modules\v1\models\User;
use Yii;
use yii\db\Transaction;


class BoxController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actionIndex()
    {
        $userSlug = Yii::$app->request->get('user_slug');

        if (empty($userSlug)) {
            $modelUser = Yii::$app->user->identity;
        } else {
            $modelUser = User::find()->where(['slug' => $userSlug, 'status' => User::STATUS_ACTIVE])->one();
        }

        if ($modelUser !== null) {

            $boxesData = [];
            $roots = Box::find()
                ->where(['user_id' => $modelUser->getId()])
                ->roots()
                ->all();
            /** @var Box $root */
            foreach ($roots as $root) {
                $desk = $root->addDesk($modelUser);
                if ($modelUser->getId() === Yii::$app->getUser()->getId()) {
                    $desk["bin"] = $root->addBinTree($modelUser->getId());
                }

                $boxesData = [
                    'name' => $root->name,
                    'key' => 'root',
                    'show' => true,
                    'desk' => $desk,
                ];
            }

            return $this->success($boxesData);
        }

        return $this->failure("Tree doesn't exist");
    }

    public function actionView($box_slug)
    {
        $page = Yii::$app->request->get('documents_page', 1);
        $userSlug = Yii::$app->request->get('user_slug');
        $data = [];

        if (empty($userSlug)) {
            $modelUser = Yii::$app->user->identity;
        } else {
            $modelUser = User::findOne(['slug' => $userSlug]);
        }

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        if ($box_slug == 'bin') {
            $model = new Box();
            if ($modelUser->id === Yii::$app->getUser()->getId()) {
                $data = $model->addBinTree($modelUser->getId());
            }
        } elseif ($box_slug == 'desk') {
            $model = Box::findOne(['name' => Box::DEFAULT_BOX_NAME, 'user_id' => $modelUser->getId()]);
        } elseif ($box_slug == 'backup-of-signed-documents') {
            $model = Box::findOne(['name' => Box::DEFAULT_SIGNED_BOX, 'user_id' => $modelUser->getId()]);
        } else {
            $model = Box::findOne(['slug' => $box_slug, 'user_id' => $modelUser->getId()]);
        }

        if ($model == null) {
            return $this->failure("Box doesn't exist");
        }

        if ($model !== null && $model->isExistenceVisibile()) {
            $model->setPage($page);
            $model->setItemsPerPage(10);

            $documents = $model->isContentVisible() ? $model->getDocuments() : [];

            $children = $model->isContentVisible() ? $model->childs($model, $modelUser) : [];

            if ($box_slug !== 'bin') {
                $data = [
                    "id" => $model->id,
                    "name" => $model->name,
                    "key" => $model->getUrl(),
                    "description" => $model->description,
                    "documents" => $documents,
                    "children" => $children,
                    "settings" => $model->getSettings()
                ];
            }
        }


        if ($model->name == Box::DEFAULT_BOX_NAME) {
            if ($modelUser->id === Yii::$app->getUser()->getId()) {
                $data["bin"] = $model->addBinTree($modelUser->getId());
            }
        }

        return $this->success($data);

    }

    public function actionCreate()
    {
        $name = Yii::$app->request->post('name');
        $description = Yii::$app->request->post('description');
        $parentId = Yii::$app->request->post('parent_id');
        $userId = Yii::$app->user->getId();

        /** @var Box $parentModel */
        $parentModel = Box::find()->where([
            'id' => $parentId,
            'user_id' => $userId
        ])->one();


        if ($parentModel == null)
            return $this->failure("Parent box does not exists");

        if ($parentModel->name == Box::DEFAULT_SIGNED_BOX) {
            return $this->failure("You can't create box in default signed box");
        }

        if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $parentModel->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }


        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {

            $model = new Box([
                'name' => $name,
                'user_id' => $userId,
                'description' => $description,
            ]);


            if ($model->appendTo($parentModel)) {

                if (!BoxPermissionSettings::setValues($model->id)) {
                    $transaction->rollBack();
                } else {
                    $data = [
                        "id" => $model->id,
                        "name" => $model->name,
                        "key" => $model->getUrl(),
                        "description" => $model->description,
                    ];

                    $transaction->commit();

                    return $this->success($data, 201);
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            return $this->failure($e->getMessage());
        }

        return $this->failure();
    }

    public function actionUpdate($id)
    {
        $name = Yii::$app->request->post('name');
        $description = Yii::$app->request->post('description');

        /** @var Box $model */
        $model = Box::find()->where(['id' => $id])->one();

        if (empty($model)) {
            return $this->failure("Box does not exists");
        }

        if (!$model->canUpdate()) {
            return $this->failure("You can not update default box", 401);
        }

        if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (!empty($name)) {
            $model->name = $name;
        }
        if (!empty($description)) {
            $model->description = $description;
        }
        if (!empty($cover)) {
            $model->cover = $cover;
        }

        $model->update();

        BoxPermissionSettings::updateValues($model->id);

        $data = [
            "id" => $model->id,
            "name" => $model->name,
            "key" => $model->getUrl(),
            "description" => $model->description,
            "settings" => $model->getSettings()
        ];

        return $this->success($data);
    }

    public function actionDelete($id)
    {
        /** @var Box $model */
        $model = Box::find()->where(['id' => $id])->one();


        if (empty($model)) {
            return $this->failure("Box does not exists");
        }

        if (!$model->canDelete()) {
            return $this->failure("You can not delete default box", 401);
        }

        if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model->updateAttributes(['is_moved_to_bin' => 1]);

        return $this->success();
    }

    public function actionRecover($id)
    {

        /** @var Box $model */
        $model = Box::find()->where(['id' => $id])->one();

        if (empty($model)) {
            return $this->failure("Box does not exists");
        }

        if (!$model->canUpdate()) {
            return $this->failure("You can not recover default box", 401);
        }

        if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model->updateAttributes(['is_moved_to_bin' => 0]);

        return $this->success();
    }

    public function actionMove($id)
    {
        $boxBeforeId = Yii::$app->request->post('box_before_id');
        $boxParentId = Yii::$app->request->post('box_parent_id');

        /** @var Box $model */
        $model = Box::find()->where(['id' => $id])->one();

        if ($model === null) {
            return $this->failure("Box does not exists");
        }

        if (!$model->canUpdate()) {
            return $this->failure("You can not move default box", 401);
        }

        if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (!empty($boxBeforeSlug)) {

            if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $boxBeforeId)) {
                return $this->failure("You are not allowed to perform this action", 401);
            }


            $neighborModel = Box::find()->where([
                'id' => $boxBeforeId,
                'user_id' => Yii::$app->user->id
            ])->one();

            if ($neighborModel->is_default == 1) {
                return $this->failure("Desk must be the first", 400);
            }


            if ($neighborModel == null) {
                return $this->failure("Parent box does not exists");
            }


            $model->insertBefore($neighborModel);

            return $this->success();
        }

        if (!empty($boxParentSlug)) {

            if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $boxParentId)) {
                return $this->failure("You are not allowed to perform this action", 401);
            }


            $parentModel = Box::find()->where([
                'id' => $boxParentId,
                'user_id' => Yii::$app->user->id
            ])->one();

            if ($parentModel->is_default == 1) {
                return $this->failure("Desk must be the first", 400);
            }

            if ($parentModel == null) {
                return $this->failure("Parent box does not exists");
            }


            $model->appendTo($parentModel);

            return $this->success();
        }

        return $this->failure();
    }


    public function actionValuesForOptions()
    {
        return $this->success(BoxPermissionSettings::optionsForAccessSettings());
    }

}