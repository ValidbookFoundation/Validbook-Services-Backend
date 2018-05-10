<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\conversation;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "conversation_to_message_user".
 *
 * @property integer $id
 * @property integer $conversation_id
 * @property integer $user_id
 * @property integer $is_deleted
 * @property integer $is_new
 * @property integer $is_seen
 * @property integer $is_left
 *
 * @property Conversation $conversation
 */
class ConversationToMessageUser extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conversation_to_message_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversation_id', 'user_id'], 'required'],
            [['conversation_id', 'user_id', 'is_deleted', 'is_new', 'is_seen', 'is_left'], 'integer'],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversation::className(), 'targetAttribute' => ['conversation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'conversation_id' => 'Conversation ID',
            'user_id' => 'User ID',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['id' => 'conversation_id']);
    }


}