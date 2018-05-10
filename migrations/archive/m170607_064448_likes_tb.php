<?php

use yii\db\Migration;

class m170607_064448_likes_tb extends Migration
{


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('{{%like}}', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'story_id' => $this->integer()->notNull(),
            'photo_id' => $this->integer()->notNull(),
            'object_id' => $this->integer()->notNull(),
            'model' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('{{%like_sender_id}}', '{{%like}}', 'sender_id', '{{%user}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%like}}');
    }

}
