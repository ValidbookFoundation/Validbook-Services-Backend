<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use yii\console\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class StoryPermissionInsertController extends Controller
{
    public function actionIndex()
    {
        $storyModelsIds = (new Query())
            ->select('id')
            ->from('story')
            ->all();
        $storyModelsIds = ArrayHelper::getColumn($storyModelsIds, 'id');

//        $storyPermissions = (new Query())
//            ->select(
//                's.id,
//                     sb.book_id,
//                     bps.permission_state,
//                     bps.custom_permission_id')
//            ->from('story s')
//            ->leftJoin('story_book sb', 's.id = sb.story_id')
//            ->leftJoin('book_permission_settings bps', 'bps.book_id = sb.book_id')
//            ->where(['bps.permission_id' => 2])
//            ->all();

        // $storyPermissions = ArrayHelper::map($storyPermissions, 'book_id', 'permission_state', 'id');

        foreach ($storyModelsIds as $storyId) {
//            if (array_key_exists($storyId, $storyPermissions)) {
//                if (in_array(1, $storyPermissions[$storyId])) {
//                    $perState = 1;
//                }
//                \Yii::$app->db->createCommand()->insert('story_permission_settings', [
//                    'story_id' => $storyId,
//                    'permission_id' => 1,
//                    'permission_state' => $perState
//                ])->execute();
//            } else {
            \Yii::$app->db->createCommand()->insert('story_permission_settings', [
                'story_id' => $storyId,
                'permission_id' => 1,
                'permission_state' => 1
            ])->execute();
        }
    }
    //}
}