<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Class People
 * @package app\modules\v1\models
 */
class People
{

    use PaginationTrait;

    const TYPE_BLOCK = 'block';
    const TYPE_ALL = 'all';
    const TYPE_SUGG = 'suggested';


    private $userIds = [];
    private $requestedUserId;

    public function getUserIds()
    {
        return $this->userIds;
    }

    public function __construct($userId, $type, $page = 1)
    {
        $this->setPage($page);
        $this->setItemsPerPage(16);

        $this->requestedUserId = $userId;
        $modelUserId = Yii::$app->getUser()->getId();

        if ($type == self::TYPE_BLOCK) {
            if ($modelUserId == $this->requestedUserId) {
                //show people block for me
                $this->userIds = $this->getSelfUserPeopleBlock();
            } //show people block for requested user
            else {
                $this->userIds = $this->mixedPeopleList($modelUserId);
            }
        } elseif ($type == self::TYPE_ALL) {
            if ($modelUserId == $this->requestedUserId) {
                //show people block for me
                $this->userIds = $this->getSelfUserPeopleAll();
            } //show people block for requested user
            else {
                $this->userIds = $this->mixedPeopleListAll($modelUserId);
            }
        } elseif ($type == self::TYPE_SUGG) {
            $this->userIds = $this->suggestedList();
        }

    }

    private function getSelfUserPeopleBlock()
    {
        $myFriends = $this->getUserFriends($this->requestedUserId);
        $userFollowings = [];

        if (count($myFriends) < 5) {
            $limit = 5;
            $userFollowings = $this->getUsersFollows($limit, $this->requestedUserId);

        }

        $arr = array_unique(array_merge($myFriends, $userFollowings));

        $arr = array_slice($arr, null, 5);

        $limitForOther = 9 - count($arr);

        $randomUserIds = $this->getOtherPeople($limitForOther, $this->requestedUserId);

        $arr = array_unique(array_merge($arr, $randomUserIds));

        if (count($arr) < 9) {
            $limit = 20;
            $userFollowings = $this->getUsersFollows($limit, $this->requestedUserId);
            $arr = array_unique(array_merge($myFriends, $userFollowings));
        }

        $arr = array_slice($arr, null, 9);

        return $arr;
    }

    private function mixedPeopleList($modelUserId)
    {
        $arrFollow = [];

        //user Friends
        $userFriends = $this->getUserFriends($this->requestedUserId);

        //my Friends
        $myFriends = $this->getUserFriends($modelUserId);

        $mutualFriends = array_intersect($userFriends, $myFriends);

        $arrFriends = array_unique(array_merge($mutualFriends, $userFriends));

        if (count($arrFriends) < 5) {
            $limit = 5 - count($arrFriends);
            $myFollowings = $this->getUsersFollows($limit, $modelUserId);
            $userFollowings = $this->getUsersFollows($limit, $this->requestedUserId);


            $arrMutualFollow = array_intersect($userFollowings, $myFollowings);

            $arrFollow = array_unique(array_merge($arrMutualFollow, $userFollowings));

            if (count($arrFollow) > $limit) {
                $arrFollow = array_slice($arrFollow, null, $limit);
            }
        }

        $arr = array_unique(array_merge($arrFriends, $arrFollow));

        $limitForOther = 9 - count($arr);

        $randomMyFriendIds = $this->getOtherPeople($limitForOther, $modelUserId);
        $randomUFriendIds = $this->getOtherPeople($limitForOther, $this->requestedUserId);

        $mutualOthers = array_intersect($randomUFriendIds, $randomMyFriendIds);

        $arrOthers = array_unique(array_merge($mutualOthers, $randomUFriendIds));

        $arr = array_unique(array_merge($arr, $arrOthers));

        $arr = array_diff($arr, [$this->requestedUserId]);

        if (count($arr) < 9) {
            $limit = 9 - count($arr);
            $myFollowings = $this->getUsersFollows($limit, $modelUserId);
            $userFollowings = $this->getUsersFollows($limit, $this->requestedUserId);


            $arrMutualFollow = array_intersect($userFollowings, $myFollowings);

            $arrFollow = array_unique(array_merge($arrMutualFollow, $userFollowings));

            if (count($arrFollow) > $limit) {
                $arrFollow = array_slice($arrFollow, null, $limit);
            }
            $arr = array_unique(array_merge($arr, $arrFollow));
        } else {
            $arr = array_slice($arr, null, 9);
        }

        return $arr;

    }

    private function getUserFriends($userId)
    {
        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0])
            ->union($subQuery1, true);

        $result = (new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $userId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('me, he')
            ->having('count(relation) > 1')
            ->orderBy('f_date DESC')
            ->limit(5)
            ->all();

        $result = ArrayHelper::getColumn($result, 'he');

        return $result;
    }

    private function getUsersFollows($limit, $userId)
    {
        $followings = ArrayHelper::getColumn((new Query())
            ->select('f.followee_id')
            ->from('follow f')
            ->innerJoin('user u', 'f.followee_id = u.id')
            ->where(['f.user_id' => $userId, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.followee_id', $userId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->orderBy('f.created_at DESC')
            ->limit($limit)
            ->all(), 'followee_id');

        $result = array_unique($followings);

        return $result;
    }


    private function getOtherPeople($limitForOther, $userId)
    {
        //section user counts stories
        $month = time() - 30 * 24 * 3600;

        $followingStoriesPeopleIds = [];

        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);


        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery4 = $subQuery2->union($subQuery1, true);

        $subQuery3 = (new Query())
            ->select('s.id, s.user_id, s.created_at')
            ->from('story s')
            ->innerJoin('story_permission_settings ss', 's.id = ss.story_id')
            ->innerJoin('user u', 's.user_id = u.id')
            ->where(['s.in_storyline' => 1, 's.in_channels' => 1, 'ss.permission_state' => 1])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->andWhere(['>', 's.created_at', $month]);

        $friendStoriesPeople = (new  Query())
            ->select(['he', 'max(f_date) as f_date', 'count(st.id) as c, "friends" as relation'])
            ->from(['rel' => $subQuery4, 'st' => $subQuery3])
            ->where('me <> he')
            ->andWhere(['me' => $userId])
            ->andWhere('he = st.user_id')
            ->groupBy('me, he')
            ->having('count(relation) > 1')
            ->orderBy('c DESC')
            ->limit(4)
            ->all();

        $friendStoriesPeopleIds = ArrayHelper::getColumn($friendStoriesPeople, 'he');


        //if count < 4 use following people for story count
        if (count($friendStoriesPeople) < 4) {
            $followingStoriesPeople = (new Query())
                ->select(['he', 'max(f_date) as f_date', 'count(st.id) as c'])
                ->from(['rel' => $subQuery1, 'st' => $subQuery3])
                //   ->innerJoin('user u', 'he = u.id')
                ->where('me <> he')
                ->andWhere(['me' => $userId])
                ->andWhere('he = st.user_id')
                //     ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->groupBy('me, he')
                ->orderBy('c DESC')
                ->limit(4)
                ->all();

            $followingStoriesPeopleIds = ArrayHelper::getColumn($followingStoriesPeople, 'he');
        }

        //most following people for my friend
        $friends = ArrayHelper::getColumn((new Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery4])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $userId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('me, he')
            ->having('count(relation) > 1')
            ->all(), 'he');

        $mostFriendFollowing = (new Query())
            ->select('followee_id, count(user_id) as c')
            ->from('follow')
            ->innerJoin('user u', 'followee_id = u.id')
            ->where(['followee_id' => $friends])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('followee_id')
            ->orderBy('c DESC')
            ->limit(5)
            ->all();

        $mostFriendFollowingIds = ArrayHelper::getColumn($mostFriendFollowing, 'followee_id');

        $result = array_unique(array_merge($friendStoriesPeopleIds, $followingStoriesPeopleIds, $mostFriendFollowingIds));

        if (count($result) < $limitForOther) {
            $followersStoriesPeople = (new Query())
                ->select(['he', 'max(f_date) as f_date', 'count(st.id) as c'])
                ->from(['rel' => $subQuery1, 'st' => $subQuery3])
                //  ->innerJoin('user u', 'he = u.id')
                ->where('me <> he')
                ->andWhere(['me' => $userId])
                ->andWhere('he = st.user_id')
                // ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->groupBy('me, he')
                ->orderBy('c DESC')
                ->limit(4)
                ->all();

            $followersStoriesPeopleIds = ArrayHelper::getColumn($followersStoriesPeople, 'followee_id');
            $result = array_unique(array_merge($result, $followersStoriesPeopleIds));
        }

        return $result;
    }

    private function getSelfUserPeopleAll()
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        //my friends
        $arr = $this->getUserFriendsAll($this->requestedUserId);

        //userFollowings
        $userFollowings = [];

        //userFollowers
        $userFollowers = [];

        if (count($arr) < $this->getItemsPerPage()) {
            $userFollowings = $this->getUsersFollowsAll($this->requestedUserId);
        }

        $arr = array_unique(array_merge($arr, $userFollowings));

        if (count($arr) < $this->getItemsPerPage()) {
            $userFollowers = $this->getUsersFollowersAll($this->requestedUserId);
        }

        $arr = array_unique(array_merge($arr, $userFollowers));

        $arr = array_diff($arr, [$this->requestedUserId]);

        if (count($arr) > $this->getItemsPerPage()) {
            $arr = array_slice($arr, null, $this->getItemsPerPage());
        }


        return $arr;
    }

    private function mixedPeopleListAll($modelUserId)
    {

        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        //mutual Friends
        $arr = $this->getFriends($modelUserId, $this->requestedUserId);

        if (count($arr) < $this->getItemsPerPage()) {
            $followingsHis = $this->getUsersFollowsAll($this->requestedUserId);
            $followingsMy = $this->getUsersFollowsAll($modelUserId);

            $followingsMutual = array_intersect($followingsHis, $followingsMy);

            //mutual followings
            $arr = array_unique(array_merge($arr, $followingsMutual));

            if (count($arr) < $this->getItemsPerPage()) {
                //his following
                $arr = array_unique(array_merge($arr, $followingsHis));
            }

            if (count($arr) < $this->getItemsPerPage()) {
                $followers = $this->getUsersFollowersAll($this->requestedUserId);
                //his followers
                $arr = array_unique(array_merge($arr, $followers));
            }
        }

        if (count($arr) > $this->getItemsPerPage()) {
            $arr = array_slice($arr, null, $this->getItemsPerPage());
        }

        return $arr;
    }

    private function getUserFriendsAll($requestedUserId)
    {
        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0])
            ->union($subQuery1, true);

        $result = (new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('me, he')
            ->having('count(relation) > 1')
            ->orderBy('f_date DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $result = array_unique(ArrayHelper::getColumn($result, 'he'));

        return $result;
    }

    private function getUsersFollowsAll($requestedUserId)
    {
        $followings = ArrayHelper::getColumn((new Query())
            ->select('f.followee_id')
            ->from('follow f')
            ->innerJoin('user u', 'f.followee_id = u.id')
            ->where(['f.user_id' => $requestedUserId, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.followee_id', $requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->all(), 'followee_id');

        $friendOrderByFollow = (new  Query())
            ->select(['followee_id, count(user_id) as c'])
            ->from('follow')
            ->innerJoin('user u', 'followee_id = u.id')
            ->where(['followee_id' => $followings])
            ->andWhere(['!=', 'user_id', 'followee_id'])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('followee_id')
            ->orderBy('c DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0])
            ->union($subQuery1, true);

        $fr = (new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('me, he')
            ->having('count(relation) > 1')
            ->orderBy('f_date DESC')
            ->all();

        $frId = array_unique(ArrayHelper::getColumn($fr, 'he'));

        $friendOrderByFollowIds =  array_unique(ArrayHelper::getColumn($friendOrderByFollow, 'followee_id'));

        $result = array_diff($friendOrderByFollowIds, $frId);

        return $result;
    }

    private function getFriends($modelUserId, $requestedUserId)
    {
        $friendOrderByFollowIds = [];

        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0])
            ->union($subQuery1, true);

        //for mutual friend
        $hisFriends = (new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('he')
            ->orderBy('f_date DESC')
            ->having('count(relation) > 1')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $myFriends = (new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $modelUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('he')
            ->having('count(relation) > 1')
            ->orderBy('f_date DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $hisFriendsIds = ArrayHelper::getColumn($hisFriends, 'he');
        $myFriendsIds = ArrayHelper::getColumn($myFriends, 'he');

        $arrMutualFriends = array_intersect($hisFriendsIds, $myFriendsIds);

        if (count($arrMutualFriends) < $this->getItemsPerPage()) {
            //friends ordering by count of followers
            //for mutual friend
            $hisFriendsA = (new  Query())
                ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
                ->from(['rel' => $subQuery2])
                ->innerJoin('user u', 'he = u.id')
                ->where('me <> he')
                ->andWhere(['me' => $requestedUserId])
                ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->groupBy('he')
                ->orderBy('f_date DESC')
                ->having('count(relation) > 1')
                ->all();

            $hisFriendsIdsA = ArrayHelper::getColumn($hisFriendsA, 'he');

            $friendOrderByFollow = (new  Query())
                ->select(['followee_id, count(user_id) as c'])
                ->from('follow')
                ->innerJoin('user u', 'followee_id = u.id')
                ->where(['followee_id' => $hisFriendsIdsA])
                ->andWhere(['!=', 'user_id', 'followee_id'])
                ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->groupBy('followee_id')
                ->orderBy('c DESC')
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->all();

            $friendOrderByFollowIds = ArrayHelper::getColumn($friendOrderByFollow, 'followee_id');
        }

        $result = array_unique(array_merge($arrMutualFriends, $friendOrderByFollowIds));

        return $result;
    }

    private function getUsersFollowersAll($requestedUserId)
    {
        $followers = ArrayHelper::getColumn((new Query())
            ->select('f.user_id')
            ->from('follow f')
            ->innerJoin('user u', 'f.user_id = u.id')
            ->where(['f.followee_id' => $requestedUserId, 'f.is_follow' => 1, 'f.is_block' => 0])
            ->andWhere(['!=', 'f.user_id', $requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->all(), 'user_id');

        $friendOrderByFollow = (new  Query())
            ->select(['followee_id, count(user_id) as c'])
            ->from('follow')
            ->innerJoin('user u', 'followee_id = u.id')
            ->where(['followee_id' => $followers])
            ->andWhere(['!=', 'user_id', 'followee_id'])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('followee_id')
            ->orderBy('c DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        $friendOrderByFollowIds = ArrayHelper::getColumn($friendOrderByFollow, 'followee_id');

        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0])
            ->union($subQuery1, true);

        $fr = (new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('me, he')
            ->having('count(relation) > 1')
            ->orderBy('f_date DESC')
            ->all();

        $frId = array_unique(ArrayHelper::getColumn($fr, 'he'));

        $friendOrderByFollowIds =  array_unique(ArrayHelper::getColumn($friendOrderByFollow, 'followee_id'));

        $result = array_diff($friendOrderByFollowIds, $frId);


        return $result;
    }

    private function suggestedList()
    {
        //friends of friends section
        $myFriends = $this->getUserFriendsAll($this->requestedUserId);

        //setPagination
        $this->setPagination($this->getItemsPerPage(), $this->getPage());


        $subQuery1 = (new Query())
            ->select(['followee_id me', 'user_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0]);

        $subQuery2 = (new Query())
            ->select(['user_id me', 'followee_id he', 'created_at f_date'])
            ->from('follow')
            ->where(['is_follow' => 1, 'is_block' => 0])
            ->union($subQuery1, true);


        $friendOfFriendsIds = ArrayHelper::getColumn((new  Query())
            ->select(['he', 'max(f_date) as f_date, "friends" as relation'])
            ->from(['rel' => $subQuery2])
            ->innerJoin('user u', 'he = u.id')
            ->where('me <> he')
            ->andWhere(['me' => $myFriends])
            ->andWhere(['!=', 'he', $this->requestedUserId])
            ->andWhere(['u.status' => User::STATUS_ACTIVE])
            ->groupBy('he')
            ->having('count(relation) > 1')
            ->orderBy('f_date DESC')
            ->limit(200)
            ->all(), 'he');

        $friendOfFriendsIds = array_slice($friendOfFriendsIds, $this->getOffset(), $this->getItemsPerPage());

        //friend
        $friendsOfFriends = array_diff($friendOfFriendsIds, $myFriends);

        if (count($friendsOfFriends) > $this->getItemsPerPage()) {
            $friendsOfFriends = array_slice($friendsOfFriends, $this->getOffset(), $this->getItemsPerPage());
        } else {
            //following section

            $myFollowingIds = ArrayHelper::getColumn((new Query())
                ->select('followee_id')
                ->from('follow')
                ->innerJoin('user u', 'followee_id = u.id')
                ->where(['user_id' => $this->requestedUserId, 'is_follow' => 1, 'is_block' => 0])
                ->andWhere(['!=', 'followee_id', $this->requestedUserId])
                ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->all(), 'followee_id');

            $arr = array_diff($friendsOfFriends, $myFollowingIds);

            $followingOfFriendsIds = ArrayHelper::getColumn((new Query())
                ->distinct()
                ->select('followee_id')
                ->from('follow')
                ->innerJoin('user u', 'followee_id = u.id')
                ->where(['user_id' => $myFriends, 'is_follow' => 1, 'is_block' => 0])
                ->andWhere(['!=', 'followee_id', $this->requestedUserId])
                ->andWhere(['u.status' => User::STATUS_ACTIVE])
                ->limit(200)
                ->all(), 'followee_id');

            $followingOfFriendsIds = array_slice($followingOfFriendsIds, $this->getOffset(), $this->getItemsPerPage());


            $uniqueFollowing = array_diff($followingOfFriendsIds, $myFollowingIds);

            $arr = array_unique(array_merge($arr, $uniqueFollowing));

            if (count($arr) > $this->getItemsPerPage()) {
                $arr = array_slice($arr, $this->getOffset(), $this->getItemsPerPage());
            } else {
                $followingOfFollowingIds = ArrayHelper::getColumn((new Query())
                    ->select('followee_id')
                    ->distinct()
                    ->from('follow')
                    ->innerJoin('user u', 'followee_id = u.id')
                    ->where(['user_id' => $myFollowingIds, 'is_follow' => 1, 'is_block' => 0])
                    ->andWhere(['!=', 'followee_id', $this->requestedUserId])
                    ->andWhere(['u.status' => User::STATUS_ACTIVE])
                    ->limit(200)
                    ->all(), 'followee_id');


                $followingOfFollowingIds = array_slice($followingOfFollowingIds, $this->getOffset(), $this->getItemsPerPage());

                $uniqueFollowingOfFollowing = array_diff($followingOfFollowingIds, $myFollowingIds);

                $arr = array_unique(array_merge($arr, $uniqueFollowingOfFollowing));

                if (count($arr) > $this->getItemsPerPage()) {
                    $arr = array_slice($arr, $this->getOffset(), $this->getItemsPerPage());
                } else {
                    $followersIds = ArrayHelper::getColumn((new Query())
                        ->select('user_id, follow.created_at')
                        ->from('follow')
                        ->innerJoin('user u', 'user_id = u.id')
                        ->where(['followee_id' => $this->requestedUserId, 'is_follow' => 1, 'is_block' => 0])
                        ->andWhere(['!=', 'user_id', $this->requestedUserId])
                        ->andWhere(['u.status' => User::STATUS_ACTIVE])
                        ->limit($this->getLimit())
                        ->offset($this->getOffset())
                        ->orderBy('follow.created_at DESC')
                        ->all(), 'user_id');

                    $arrFollowers = array_diff($followersIds, $myFollowingIds);

                    $arr = array_unique(array_merge($arr, $arrFollowers));

                    if (count($arr) > $this->getItemsPerPage()) {
                        $arr = array_slice($arr, null, $this->getItemsPerPage());
                    }
                }

            }
        }

        return $arr;
    }

}