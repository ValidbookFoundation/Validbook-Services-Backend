<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\components;

use Yii;
use yii\filters\auth\HttpBearerAuth;

class UserRestController extends RestController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'authenticator' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['options']
            ]
        ]);
    }

    protected function hasOwnerAccessRights($modelName, $property, $id)
    {
        if (($model = $modelName::findOne($id)) !== null && $model->$property == Yii::$app->user->id) {
            return true;
        }

        return false;
    }

}