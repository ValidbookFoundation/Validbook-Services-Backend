<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models;

use app\modules\v1\traits\PaginationTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_photo".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property string $url
 * @property integer $created_at
 */
class UserPhoto extends ActiveRecord
{

    const TYPE_AVATAR = 1;
    const TYPE_COVER = 2;
    const USER_ENTITY = 'user';

    use PaginationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'url'], 'required'],
            [['type', 'user_id', 'created_at'], 'integer'],
            [['url'], 'string', 'max' => 255],
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
                ],
            ],
        ];
    }

    public function getFormattedCard()
    {
        return [
            'id' => $this->id,
            'type' => 'image/jpeg',
            'entity' => self::USER_ENTITY,
            'entity_id' => $this->user_id,
            'url' => $this->url,
            'created' => Yii::$app->formatter->asDate($this->created_at)

        ];
    }

    public function getAllImages($userId)
    {
        $uPhotos = [];
        $this->setItemsPerPage(20);

        $userPhotos = UserPhoto::find()
            ->where(['user_id' => $userId])
            ->orderBy('created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var UserPhoto $photo */
        foreach ($userPhotos as $photo) {
            $smallPicture = $this->getSmallPicture($photo);

            $uPhotos[] = [
                'id' => $photo->id,
                'entity' => self::USER_ENTITY,
                'entity_id' => $photo->user_id,
                'picture_original' => $photo->url,
                'picture_small' => $smallPicture,
                'created' => Yii::$app->formatter->asDate($photo->created_at)
            ];
        }

        return $uPhotos;
    }

    public function getImagesForType($userId, $type)
    {
        $uPhotos = [];
        $this->setItemsPerPage(20);

        $userPhotos = UserPhoto::find()
            ->where(['user_id' => $userId, 'type' => $type])
            ->orderBy('created_at DESC')
            ->limit($this->getLimit())
            ->offset($this->getOffset())
            ->all();

        /** @var UserPhoto $photo */
        foreach ($userPhotos as $photo) {
            $smallPicture = $this->getSmallPicture($photo);

            $uPhotos[] = [
                'id' => $photo->id,
                'entity' => UserPhoto::USER_ENTITY,
                'entity_id' => $photo->user_id,
                'picture_original' => $photo->url,
                'picture_small' => $smallPicture,
                'created' => Yii::$app->formatter->asDate($photo->created_at)
            ];

        }

        return $uPhotos;
    }

    private function getSmallPicture($photo)
    {
        $smallPicture = null;

        if ($photo->type == UserPhoto::TYPE_AVATAR) {
            /** @var Avatar $usersAvatar */
            $usersAvatar = Avatar::find()
                ->where(['size' => '220x220', 'original_id' => $photo->id])
                ->one();
            $smallPicture = $usersAvatar->url ?? null;
        } elseif ($photo->type == UserPhoto::TYPE_COVER) {
            /** @var Cover $usersCover */
            $usersCover = Cover::find()
                ->where(['size' => '1760x220', 'original_id' => $photo->id])
                ->one();
            $smallPicture = $usersCover->url ?? null;
        }
        return $smallPicture;
    }
}