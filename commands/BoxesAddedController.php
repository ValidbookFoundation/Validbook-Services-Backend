<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use app\modules\v1\models\box\Box;
use app\modules\v1\models\User;
use yii\console\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class BoxesAddedController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->all();

        /** @var User $user */
        foreach ($users as $user) {
            //create default box
            $rootModel = new Box([
                'name' => 'root',
                'user_id' => $user->id,
                'created_at' => time(),
                'is_root' => 1
            ]);

            $rootModel->createRoot($user->id);


            $model = new Box();
            $model->name = $model->getDefaultBoxName();
            $model->user_id = $user->id;
            $model->is_default = 1;

            $parentModel = Box::findOne(['user_id' => $user->getId()]);

            $model->prependTo($parentModel);

            $dimBoxPermissions = (new Query())
                ->select('id')
                ->from('dim_permission_box')
                ->all();

            $dimBoxPermissionsIds = ArrayHelper::getColumn($dimBoxPermissions, 'id');
            foreach ($dimBoxPermissionsIds as $id) {
                switch ($id) {
                    case 1 :
                        $perState = 1;
                        break;
                    case 2 :
                        $perState = 1;
                        break;
                    default :
                        $perState = 0;
                }

                \Yii::$app->db->createCommand()->insert('box_permission_settings', [
                    'box_id' => $model->id,
                    'permission_id' => $id,
                    'permission_state' => $perState
                ])->execute();
            }

            //default inbox for signed docs
            $modelSigned = new Box();
            $modelSigned->name = $model->getSignedDocsBoxName();
            $modelSigned->user_id = $user->id;
            $modelSigned->is_default = 1;

            $modelSigned->prependTo($model);

            foreach ($dimBoxPermissionsIds as $id) {
                switch ($id) {
                    case 1 :
                        $perState = 0;
                        break;
                    case 2 :
                        $perState = 0;
                        break;
                    default :
                        $perState = 0;
                }

                \Yii::$app->db->createCommand()->insert('box_permission_settings', [
                    'box_id' => $modelSigned->id,
                    'permission_id' => $id,
                    'permission_state' => $perState
                ])->execute();

            }
        }
    }
}