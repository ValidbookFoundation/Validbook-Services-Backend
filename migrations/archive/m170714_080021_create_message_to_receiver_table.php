<?php

use yii\db\Migration;

/**
 * Handles the creation of table `message_to_receiver`.
 */
class m170714_080021_create_message_to_receiver_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('message_to_receiver', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'is_left' => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey('{{%message_to_receiver_message_id}}', '{{%message_to_receiver}}', 'message_id', '{{%message}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('message_to_receiver');
    }
}
