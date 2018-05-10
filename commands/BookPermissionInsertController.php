<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */
namespace app\commands;

use yii\console\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class BookPermissionInsertController extends Controller
{
    public function actionIndex()
    {
        $bookModelsIds = (new Query())
            ->select('id')
            ->from('book')
            ->all();
        $bookModelsIds = ArrayHelper::getColumn($bookModelsIds, 'id');

        $bookPermissionsIds = (new Query())
            ->select('book_id')
            ->from('book_permission_settings')
            ->all();

        $bookPermissionsIds =  ArrayHelper::getColumn($bookPermissionsIds, 'book_id');

        $dimBookPermissionsIds = (new Query())
            ->select('id')
            ->from('dim_permission_book')
            ->all();

        $dimBookPermissionsIds = ArrayHelper::getColumn($dimBookPermissionsIds, 'id');

        foreach ($bookModelsIds as $bookId){
            if(!in_array($bookId, $bookPermissionsIds)){
                foreach ($dimBookPermissionsIds as $id){
                    switch ($id){
                        case 1 :
                            $perState = 1;
                            break;
                        case 2 :
                            $perState = 1;
                            break;
                        default :
                            $perState = 0;
                    }
                    \Yii::$app->db->createCommand()->insert('book_permission_settings', [
                        'book_id' => $bookId,
                        'permission_id' => $id,
                        'permission_state' => $perState
                    ])->execute();
                }
            }
        }
    }
}