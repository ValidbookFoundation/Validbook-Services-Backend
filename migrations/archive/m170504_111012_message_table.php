<?php

use yii\db\Migration;

class m170504_111012_message_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'is_new' => $this->boolean()->defaultValue(true),
            'is_tech' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer()->notNull(),
            'conversation_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('{{%message_user_id}}', '{{%message}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'is_new' => $this->boolean()->defaultValue(true),
            'is_seen' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('{{%notification_sender_id}}', '{{%notification}}', 'sender_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%notification_receiver_id}}', '{{%notification}}', 'receiver_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%message}}');
        $this->dropTable('{{%notification}}');
    }
}
