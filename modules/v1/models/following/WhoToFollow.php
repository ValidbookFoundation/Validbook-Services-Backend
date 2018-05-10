<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */


/*
If user follows none.

Create list A – all users that are registered on VB.
Create list A1 – sort list A by amount of public stories user logged (users with the most stories at the top).
Create list A2 – sort list A by amount of public stories user logged within 10 last days (users with the most stories at the top).
Create list A3 – sort list A by amount followers user has (users with the most followers at the top).
Create list A4 – sort list A by amount followers user received in last 10 days (users with the most followers at the top).
Create list B, by taking 1 person from the top of list A1, 4 from – A2, 2 from – A3 and 5 from – A4. Make sure that list B does not contain duplicates.
Create list X, by taking 3 people in random way from list B.

If user follows someone.

Create list A – all people that I follow.
Create list B1 – people who were followed by people from list A in the last 5 days.
Create list B2 – people who are followed by people from list A.
Create list C2.1 – sort list B2 by who has logged the most stories in the last 30 days.
Create list C2.2 - sort list B2 by who has the most followers.
Create list D by taking 1 person from list B1 in random way; 1 person randomly taken from 10 top people of list C2.1; 1 person randomly taken from 10 top people of list C2.2.
Create list E by taking 1 person from list X (from 'If I follow noone algorithm').
Create list X by taking 2 people randomly from list D and adding 1 person from list E.
*/

namespace app\modules\v1\models\following;

use app\modules\v1\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class WhoToFollow
{

    private $userIds = [];
    private $peopleIFollowed;
    public static $exclude = [];

    public function __construct($exclude = [])
    {
        $this->peopleIFollowed = $this->userIdsIFollow();

        if (!empty($exclude) && is_array($exclude))
            self::$exclude = $exclude;

        $this->userIds = (!empty($this->peopleIFollowed)) ? $this->mixedListFollowsSmbd() : $this->mixedListFollowsNone();
    }

    public function getUserIds($limit = 3)
    {
        return array_splice($this->userIds, 0, $limit);
    }

    private function userIdsIFollow()
    {
        $uid = Yii::$app->user->id;

        $result = (new Query())
            ->select('followee_id, user_id')
            ->from('follow')
            ->innerJoin('user u', 'followee_id = u.id')
            ->where(['user_id' => $uid, 'is_follow' => 1])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->andWhere(['!=', 'followee_id', $uid])
            ->all();

        $users = [];

        foreach ($result as $item) {
            if ($item['followee_id'] == $item['user_id'])
                continue;

            $users[] = $item['followee_id'];
        }

        return $users;
    }

    private function mixedListFollowsNone($limit = 20)
    {
        $arr = [];

        $list1 = self::getUsersByStoriesCount(false, 2);
        if (isset($list1[0]))
            $arr[] = $list1[0];

        $list2 = self::getUsersByStoriesCount(10, 10);
        foreach ($list2 as $value) {
            if (!in_array($value, $arr) && (count($arr) <= 4))
                $arr[] = $value;
        }

        $list3 = self::getUsersByFollowersHas(false, 14, [], 'followers');
        foreach ($list3 as $value) {
            if (!in_array($value, $arr) && (count($arr) <= 6))
                $arr[] = $value;
        }

        $list4 = self::getUsersByFollowersHas(10, 24, [], 'followers');
        foreach ($list4 as $value) {
            if (!in_array($value, $arr) && (count($arr) <= 11))
                $arr[] = $value;
        }

        shuffle($arr);

        return array_splice($arr, 0, $limit);
    }

    private function mixedListFollowsSmbd()
    {
        if (!is_array($this->peopleIFollowed))
            throw new NotFoundHttpException('userIds must be an array');

        $arr = [];

        $list1 = self::getUsersByFollowersHas(5, 5, $this->peopleIFollowed, 'followers');

        $list2 = self::getUsersByFollowersHas(30, 10, $this->peopleIFollowed, 'stories');
        $list3 = self::getUsersByFollowersHas(false, 10, $this->peopleIFollowed, 'followers');

        // 1 person from list $list1 in random way;
        shuffle($list1);
        if (isset($list1[0]))
            $arr[] = $list1[0];

        // 1 person randomly taken from 10 top people of $list2;
        shuffle($list2);
        foreach ($list2 as $key => $value) {
            if (isset($list2[$key]) && !in_array($value, $arr) && (count($arr) <= 1))
                $arr[] = $list2[$key];
        }

        // 1 person randomly taken from 10 top people of $list3.
        shuffle($list3);
        foreach ($list3 as $key => $value) {
            if (isset($list3[$key]) && !in_array($value, $arr) && (count($arr) <= 2))
                $arr[] = $list3[$key];
        }

        $arr = array_splice($arr, 0, 2);

        self::$exclude = array_merge(self::$exclude, $arr);

        $userIdFromMixedListFollowsNone = $this->mixedListFollowsNone(3 - count($arr));
        $newArr = array_merge($userIdFromMixedListFollowsNone, $arr);

        shuffle($newArr);

        return $newArr;
    }

    private static function getUsersByStoriesCount(
        $days = false,
        $limit = 10
    )
    {
        $andWhere = "";
        $uid = Yii::$app->user->id;

        if (!empty(self::$exclude) && is_array(self::$exclude)) {
            $excludeIds = implode(",", self::$exclude);
            $andWhere .= " AND u.id NOT IN ({$excludeIds}) ";
        }

        //exclude already followed users
        $andWhere .= " AND u.id NOT IN (SELECT followee_id FROM follow WHERE user_id = :uid) ";

        if ($days) {
            $timeAgo = time() - 60 * 60 * 24 * $days;
            $andWhere .= " AND s.created_at > $timeAgo ";
        }

        $sql = "SELECT u.id
                FROM user u
                LEFT JOIN story s ON u.id = s.user_id
                LEFT JOIN story_permission_settings sp ON s.id = sp.story_id
                WHERE sp.permission_state = 1 
                    AND (s.in_storyline = 1 OR s.in_channels = 1)
                    AND u.id != :uid
                    AND u.status != 0
                $andWhere
                GROUP BY u.id
                ORDER BY COUNT(s.id) DESC
                LIMIT $limit
                ";

        $command = Yii::$app->db->createCommand($sql);
        $command->bindParam(":uid", $uid, PDO::PARAM_INT);
        $result = $command->queryColumn();

        return $result;
    }

    private static function getUsersByFollowersHas(
        $days = false,
        $limit = 10,
        $followebBy = [],
        $orderBy = 'followers'
    )
    {
        $andWhere = "";
        $andJoin = "";
        $uid = Yii::$app->user->id;
        if (!empty(self::$exclude) && is_array(self::$exclude)) {
            $excludeIds = implode(",", self::$exclude);
            $andWhere .= " AND u.id NOT IN ({$excludeIds}) ";
        }

        if ($days) {
            $timeAgo = time() - 60 * 60 * 24 * $days;
            $andWhere .= " AND f.created_at > $timeAgo ";
        }

        if (!empty($followebBy)) {
            $commaSeparatedUserIds = implode(",", $followebBy);
            $andWhere .= " AND f.user_id IN ($commaSeparatedUserIds)";
            $andWhere .= " AND f.followee_id NOT IN ($commaSeparatedUserIds)";
        }

        //exclude already followed users
        $andWhere .= " AND u.id NOT IN (SELECT followee_id FROM follow WHERE user_id = :uid and is_follow = 1) ";

        if ($orderBy == 'followers')
            $orderBySql = " BY COUNT(f.followee_id) ";
        elseif ($orderBy == 'stories') {
            $andJoin .= " LEFT JOIN story s ON u.id = s.user_id ";
            $orderBySql = " BY COUNT(s.id) ";
        }

        $sql = "SELECT u.id
                FROM user u
                LEFT JOIN follow f ON u.id = f.followee_id
                $andJoin
                WHERE u.id != :uid
                AND u.status != 0
                $andWhere
                GROUP BY u.id
                ORDER $orderBySql DESC
                LIMIT $limit
                ";

        $command = Yii::$app->db->createCommand($sql);
        $command->bindParam(":uid", $uid, PDO::PARAM_INT);
        $result = $command->queryColumn();

        return $result;
    }
}
