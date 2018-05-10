<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\following;

use app\modules\v1\jobs\FillChannelJob;
use app\modules\v1\jobs\UnFillChannelJob;
use app\modules\v1\jobs\UpdateFillChannelJob;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\Channel;
use app\modules\v1\models\notification\FollowBookReceiver;
use app\modules\v1\models\notification\FollowReceiver;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\story\StoryBook;
use app\modules\v1\models\story\StoryPermissionSettings;
use app\modules\v1\models\User;
use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "follow".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $followee_id
 * @property integer $channel_id
 * @property integer $is_follow
 * @property integer $is_block
 * @property string $created_at
 *
 * @property User $followee
 * @property User $user
 * @property Channel $channel
 */
class Follow extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    use PaginationTrait;

    public static function tableName()
    {
        return 'follow';
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->is_block == 0 and $this->is_follow == 0) {
            $this->delete();
        }

        if ($this->is_follow == 1 and $this->is_block == 0) {

            Yii::$app->queue->push(new FillChannelJob([
                'followeeId' => $this->followee_id,
                'channelId' => $this->channel_id,
                'userId' => $this->user_id
            ]));
        }
    }

    /**
     * @param $followedUserId
     * @param $modelUserId
     * @return array
     */
    public static function unFollowAllForUser($followedUserId, $modelUserId)
    {

        $followChannelsForUser = Follow::findAll(['user_id' => $modelUserId, 'followee_id' => $followedUserId]);

        foreach ($followChannelsForUser as $follow) {
            $follow->delete();
        }

        $booksFollowerIds = ArrayHelper::getColumn(
            (new Query())
                ->select('id')
                ->from('book')
                ->where(['author_id' => $followedUserId])
                ->all(),
            'id');
        $followBooks = FollowBook::findAll(['user_id' => $modelUserId, 'book_id' => $booksFollowerIds]);

        foreach ($followBooks as $book) {
            $book->delete();
        }

        return ['is_follow' => false];

    }

    public function beforeDelete()
    {
        parent::beforeDelete();

        Yii::$app->queue->push(new UnFillChannelJob([
            'followeeId' => $this->followee_id,
            'channelId' => $this->channel_id,
        ]));

        return true;
    }

    public static function updateChannels($followedUserId, $userId, $channels)
    {
        $followList = Follow::find()->where([
            'user_id' => $userId,
            'followee_id' => $followedUserId
        ])->all();

        $modelUser = User::findOne($userId);

        //if empty channels from form
        if (empty($channels)) {

            /** @var Follow $follow */
            foreach ($followList as $follow) {
                $follow->delete();
            }
        } else {


            $channelIds = ArrayHelper::getColumn($channels, 'channel_id');

            /**@var Follow $follow */
            foreach ($followList as $key => $follow) {
                if (in_array($follow->channel_id, $channelIds)) {
                    foreach ($channels as $k => $channel) {
                        if ($follow->channel_id == $channel['channel_id']) {
                            if ($channel['is_block'] == 0 and $channel['is_follow'] == 0) {
                                $follow->delete();
                            }

                            if ($channel['is_block'] == 0 and $channel['is_follow'] == 1) {
                                $follow->is_block = $channel['is_block'];
                                $follow->is_follow = $channel['is_follow'];
                                $follow->update();

                                Yii::$app->queue->push(new UpdateFillChannelJob([
                                    'channelId' => $channel['channel_id'],
                                ]));

                            }
                            if ($channel['is_block'] == 1 and $channel['is_follow'] == 0) {
                                $follow->is_block = $channel['is_block'];
                                $follow->is_follow = $channel['is_follow'];
                                $follow->update();

                                Yii::$app->queue->push(new UpdateFillChannelJob([
                                    'channelId' => $channel['channel_id'],
                                ]));
                            }

                            unset($channels[$k]);
                        }
                    }
                } else {
                    $followList[$key]->delete();
                }
            }

            foreach ($channels as $channel) {
                if ($channel['is_block'] == 1 and $channel['is_follow'] == 1) {
                    return ["You can't block and follow this channel"];
                }
                $model = new Follow([
                    'user_id' => $userId,
                    'channel_id' => $channel['channel_id'],
                    'followee_id' => $followedUserId,
                    'is_follow' => $channel['is_follow'],
                    'is_block' => $channel['is_block']
                ]);
                if ($model->validate()) {
                    $model->save();

                    // send notification
                    if (empty($followList)) {
                        $notBuilder = new NotificationFactory($modelUser, $model->followee_id, $modelUser);
                        $followReceiver = new FollowReceiver();
                        $receivers = $followReceiver->getReceiver($model->followee_id);
                        $receivers = $notBuilder->filterReceivers($receivers);
                        $notBuilder->addModel($receivers);
                        $notBuilder->build();
                    }


                } else {
                    return [$model->errors];
                }
            }

            // send notification
            /** @var User $modelUser */
            $modelUser = Yii::$app->getUser()->identity;
            $notBuilder = new NotificationFactory($modelUser, $followedUserId, $modelUser);
            $followReceiver = new FollowReceiver();
            $receivers = $followReceiver->getReceiver($followedUserId);
            $receivers = $notBuilder->filterReceivers($receivers);
            $notBuilder->addModel($receivers);
            $notBuilder->build();
        }

        return [];
    }

    public static function updateBooks($followedUserId, $getId, $books)
    {
        // books section
        $booksFollowerIds = ArrayHelper::getColumn(
            (new Query())
                ->select('id')
                ->from('book')
                ->where(['author_id' => $followedUserId])
                ->all(),
            'id');

        $followBooks = FollowBook::findAll(['user_id' => $getId, 'book_id' => $booksFollowerIds]);


        $booksChannelsArray = ArrayHelper::map($books, 'book_id', 'channels');

        $booksIds = array_keys($booksChannelsArray);

        if (empty($booksIds)) {
            foreach ($followBooks as $fbook) {
                $fbook->delete();
            }
        } else {
            $result = self::operationForBook($getId, $followBooks, $booksChannelsArray, $booksIds);
            return $result;

        }
        return [];
    }


    public static function updateBook($getId, $books)
    {

        $bookIds = ArrayHelper::getColumn($books, 'book_id');

        $followBooks = FollowBook::findAll(['user_id' => $getId, 'book_id' => $bookIds]);

        $booksChannelsArray = ArrayHelper::map($books, 'book_id', 'channels');

        $booksIds = array_keys($booksChannelsArray);

        if (empty($booksIds)) {
            foreach ($followBooks as $fbook) {
                $fbook->delete();
            }
        } else {
            $result = self::operationForBook($getId, $followBooks, $booksChannelsArray, $booksIds);
            return $result;
        }
        return [];
    }

    private static function operationForBook($getId, $followBooks, $booksChannelsArray, $booksIds)
    {
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;

        /** @var FollowBook $followBook */
        foreach ($followBooks as $key => $followBook) {

            if (in_array($followBook->book_id, $booksIds)) {
                foreach ($booksChannelsArray as $bookId => $channels) {
                    if ($bookId == $followBook->book_id) {
                        $channelsIds = ArrayHelper::getColumn($channels, 'channel_id');
                        if (in_array($followBook->channel_id, $channelsIds)) {
                            $k = array_search($followBook->channel_id, $channelsIds);
                            if ($channels[$k]['is_block'] == 0 and $channels[$k]['is_follow'] == 0) {
                                $followBook->delete();

                            }

                            if ($channels[$k]['is_block'] == 0 and $channels[$k]['is_follow'] == 1) {
                                $followBook->is_block = $channels[$k]['is_block'];
                                $followBook->is_follow = $channels[$k]['is_follow'];
                                $followBook->update();


                                Yii::$app->queue->push(new UpdateFillChannelJob([
                                    'channelId' => $followBook->channel_id,
                                ]));

                            }
                            if ($channels[$k]['is_block'] == 1 and $channels[$k]['is_follow'] == 0) {
                                $followBook->is_block = $channels[$k]['is_block'];
                                $followBook->is_follow = $channels[$k]['is_follow'];
                                $followBook->update();

                                Yii::$app->queue->push(new UpdateFillChannelJob([
                                    'channelId' => $followBook->channel_id,
                                ]));

                            }
                            if ($channels[$k]['is_block'] == 1 and $channels[$k]['is_follow'] == 1) {
                                return ["You can't block and follow this channel"];
                            }
                            unset($booksChannelsArray[$followBook->book_id][$k]);
                        } else {
                            $followBook->delete();
                        }
                    }
                }
            } else {
                $followBooks[$key]->delete();
            }
        }

        foreach ($booksChannelsArray as $bookId => $channels) {
            foreach ($channels as $channel) {
                if ($channel['is_block'] == 1 and $channel['is_follow'] == 1) {
                    return ["You can't block and follow this channel"];
                }
                $followBook = new FollowBook();
                $followBook->user_id = $getId;
                $followBook->book_id = $bookId;
                $followBook->channel_id = $channel['channel_id'];
                $followBook->is_block = $channel['is_block'];
                $followBook->is_follow = $channel['is_follow'];
                if ($followBook->validate()) {
                    $followBook->save();


                    /** @var Book $modelBook */
                    $modelBook = Book::find()->where(["id" => $bookId])->one();

                    // send notification
                    $notBuilder = new NotificationFactory($modelUser, $modelBook->author_id, $modelBook);
                    $followBookReceiver = new FollowBookReceiver();
                    $receivers = $followBookReceiver->getReceiver($modelBook->author_id);
                    $receivers = $notBuilder->filterReceivers($receivers);
                    $notBuilder->addModel($receivers);
                    $notBuilder->build();
                } else {
                    return [$followBook->errors];
                }
            }
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'channel_id', 'followee_id'], 'required'],
            [['user_id', 'channel_id', 'followee_id', 'created_at', 'is_block', 'is_follow'], 'integer'],
            [['followee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['followee_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Channel::className(), 'targetAttribute' => ['channel_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'followee_id' => 'Followee ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ]
            ]
        ];
    }

//    /**
//     * @inheritdoc
//     */
    public function beforeSave($insert)
    {
        if (empty($this->user_id)) {
            $this->user_id = Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowee()
    {
        return $this->hasOne(User::className(), ['id' => 'followee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'user_id']);
    }

    public static function isFollowing($userId, $followeUserdId)
    {
        $model = self::find()->where([
            'user_id' => $userId,
            'followee_id' => $followeUserdId,
            'is_follow' => 1,
            'is_block' => 0
        ])->one();

        if (!empty($model)) {
            return true;
        }
        return false;
    }

    public static function isFollower($userId, $followeUserdId)
    {
        $model = self::find()->where([
            'user_id' => $followeUserdId,
            'followee_id' => $userId,
            'is_follow' => 1,
            'is_block' => 0
        ])->one();

        if (!empty($model)) {
            return true;
        }
        return false;
    }

    public static function isFriend($userId, $friendId)
    {
        $modelFollowing = self::find()
            ->where([
                'user_id' => $userId,
                'followee_id' => $friendId,
                'is_follow' => 1,
                'is_block' => 0
            ])->count();

        $modelFollowers = self::find()
            ->where([
                'user_id' => $friendId,
                'followee_id' => $userId,
                'is_follow' => 1,
                'is_block' => 0
            ])->count();


        if ($modelFollowing == 1 && $modelFollowers == 1) {
            if ($userId == $friendId) {
                return false;
            }
            return true;
        }

        return false;
    }

    public static function getFollowingChannels($userId, $followeUserdId)
    {
        $models = self::find()->where([
            'user_id' => $userId,
            'followee_id' => $followeUserdId,
            'is_follow' => 1
        ])->all();

        if (!empty($models)) {
            $channelsIds = [];
            /** @var Follow $follow */
            foreach ($models as $follow) {
                $channelsIds['channelsIds'][] = $follow->channel_id;
            }

            return $channelsIds;
        }

        return null;
    }

    public function userFollowers($user)
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $result = (new Query())
            ->select('u.id, u.first_name, u.last_name, u.email, u.slug, p.avatar')
            ->from('user u')
            ->leftJoin('profile p', 'p.user_id = u.id')
            ->leftJoin('follow f', 'u.id = f.user_id')
            ->where(['f.followee_id' => $user->getId(),
                'f.is_follow' => 1,
                'f.is_block' => 0,
                'u.status' => 1])
            ->andWhere(['!=', 'f.followee_id', $user->getId()])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return $result;
    }

    public function userFollowings($user)
    {
        $this->setPagination($this->getItemsPerPage(), $this->getPage());


        $result = (new Query())
            ->select('u.id, u.first_name, u.last_name, u.email, u.slug, p.avatar')
            ->from('user u')
            ->leftJoin('profile p', 'p.user_id = u.id')
            ->leftJoin('follow f', 'u.id = f.followee_id')
            ->where(['f.user_id' => $user->getId(), 'f.is_follow' => 1, 'f.is_block' => 0, 'u.status' => 1])
            ->andWhere(['!=', 'f.followee_id', $user->getId()])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        return $result;
    }


    public function userFollowingForChannel($user, $channelId)
    {
        $countFollowedBooks = $countBlockedBooks = $booksArray = [];

        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        if ($user->getDefaultChannelId() == $channelId) {
            $result = (new Query())
                ->select('u.id, u.first_name, u.last_name, u.email, u.slug, p.avatar')
                ->from('user u')
                ->leftJoin('profile p', 'p.user_id = u.id')
                ->leftJoin('follow f', 'u.id = f.followee_id')
                ->where(['f.user_id' => $user->getId(),
                    'f.channel_id' => $channelId,
                    'f.is_follow' => 1,
                    'f.is_block' => 0,
                    'u.status' => 1])
                ->andWhere(['!=', 'f.followee_id', $user->getId()])
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->all();
        } else {
            $result = (new Query())
                ->select('u.id, u.first_name, u.last_name, u.email, u.slug, p.avatar')
                ->from('user u')
                ->leftJoin('profile p', 'p.user_id = u.id')
                ->leftJoin('follow f', 'u.id = f.followee_id')
                ->where(['f.user_id' => $user->getId(),
                    'f.channel_id' => $channelId,
                    'f.is_follow' => 1,
                    'f.is_block' => 0,
                    'u.status' => 1])
                ->limit($this->getLimit())
                ->offset($this->getOffset())
                ->all();
        }

        $result = ArrayHelper::index($result, 'id');

        $books = (new Query())
            ->select('author_id, id')
            ->from('book')
            ->where(['author_id' => array_keys($result)])
            ->all();

        foreach ($books as $book) {
            $booksArray[$book['author_id']][] = $book['id'];
        }

        foreach ($booksArray as $aId => $items) {
            $countFollowedBooks[$aId] = (int)FollowBook::find()->where([
                'user_id' => $user->getId(),
                'book_id' => $items,
                'channel_id' => $channelId,
                'is_follow' => 1,
                'is_block' => 0
            ])->count();
            $countBlockedBooks[$aId] = (int)FollowBook::find()->where([
                'user_id' => $user->getId(),
                'book_id' => $items,
                'channel_id' => $channelId,
                'is_follow' => 0,
                'is_block' => 1
            ])->count();
        }


        foreach ($result as $key => $item) {
            $result[$key]['counts']['followed_books'] = $countFollowedBooks[$key];
            $result[$key]['counts']['blocked_books'] = $countBlockedBooks[$key];
        }


        return array_merge($result, []);
    }

    public function booksForChannel($getId, $channelId)
    {
        $data = [];
        $this->setPagination($this->getItemsPerPage(), $this->getPage());

        $fBooksIds = ArrayHelper::getColumn(FollowBook::find()->where([
            'user_id' => $getId,
            'channel_id' => $channelId,
            'is_follow' => 1,
            'is_block' => 0
        ])
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all(), 'book_id');

        $books = Book::findAll(['id' => $fBooksIds]);

        /** @var Book $model */
        foreach ($books as $model) {
            $data[] = [
                "id" => $model->id,
                "name" => $model->name,
                "slug" => $model->getUrl(),
                "description" => $model->description,
                "counters" => [
                    "stories" => (int)StoryBook::find()->alias('sb')
                        ->innerJoin('story_permission_settings sp', 'sb.story_id=sp.story_id')
                        ->where([
                            'sb.book_id' => $model->id,
                            "sp.permission_state" => StoryPermissionSettings::PRIVACY_TYPE_PUBLIC,
                            'sb.is_moved_to_bin' => 0
                        ])->count(),
                    "sub_books" => (int)$model->children(1)->count(),
                    "follows" => (int)FollowBook::find()->where(['book_id' => $model->id])->count()
                ],
                "settings" => $model->getSettings()
            ];

        }
        return $data;

    }

    public static function getFormattedDataForPopup($followsId)
    {
        $modelUser = Yii::$app->user->identity;
        $booksChannels = $dataChannels = $followChannels = $dataFollowForUser = [];

        $channels = Channel::findAll(['user_id' => $modelUser->getId()]);
        foreach ($channels as $key => $channel) {
            $dataChannels[(integer)$channel->id] = $channel->name;
        }

        $followChannelsForUser = Follow::findAll(['user_id' => $modelUser->getId(), 'followee_id' => $followsId]);


        foreach ($followChannelsForUser as $follow) {
            $dataFollowForUser[] = ['channel_id' => $follow->channel_id, 'is_block' => $follow->is_block, 'is_follow' => $follow->is_follow];
        }

        $booksFollowerIds = ArrayHelper::getColumn(
            (new Query())
                ->select('id')
                ->from('book')
                ->where(['author_id' => $followsId])
                ->all(),
            'id');
        $followBooks = FollowBook::findAll(['user_id' => $modelUser->getId(), 'book_id' => $booksFollowerIds]);

        foreach ($followBooks as $followBook) {
            $followChannels[$followBook->book_id][] = [
                'channel_id' => $followBook->channel_id,
                'is_block' => $followBook->is_block,
                'is_follow' => $followBook->is_follow
            ];
        }

        foreach ($followChannels as $key => $channel) {
            $booksChannels[] = ['book_id' => $key, 'channels' => $channel];
        }

        $data = [
            'all_channels' => $dataChannels,
            'books_channels' => $booksChannels,
            'user_channels' => $dataFollowForUser
        ];

        return $data;
    }

    public static function getFormattedDataForBookPopup($bookIds)
    {
        $modelUser = Yii::$app->user->identity;
        $booksChannels = $dataChannels = $followChannels = [];

        $channels = Channel::findAll(['user_id' => $modelUser->getId()]);
        foreach ($channels as $key => $channel) {
            $dataChannels[(integer)$channel->id] = $channel->name;
        }

        $followBooks = FollowBook::findAll(['user_id' => $modelUser->getId(), 'book_id' => $bookIds]);

        foreach ($followBooks as $followBook) {
            $followChannels[$followBook->book_id][] = [
                'channel_id' => $followBook->channel_id,
                'is_block' => $followBook->is_block,
                'is_follow' => $followBook->is_follow
            ];
        }

        foreach ($followChannels as $key => $channel) {
            $booksChannels[] = ['book_id' => $key, 'channels' => $channel];
        }

        return [
            'channels' => $dataChannels,
            'books_channels' => $booksChannels
        ];
    }


}
