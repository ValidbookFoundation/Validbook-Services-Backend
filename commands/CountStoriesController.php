<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

class CountStoriesController extends Controller
{
    public function actionIndex()
    {
        $connection = Yii::$app->db;

        $sql = "SELECT COUNT(*) as count_stories, user_id 
                FROM story 
                WHERE visibility_type = 1 AND in_storyline = 1 
                GROUP BY user_id";
        $users = $connection->createCommand($sql)->queryAll();

        foreach ($users as $user) {
            $sqlUpdateCount = "UPDATE user 
                                SET stories_count = '{$user['count_stories']}' 
                                WHERE id = '{$user['user_id']}'";
            $connection->createCommand($sqlUpdateCount)->query();
        }
    }
}