<?php

use yii\db\Migration;

class m170418_122357_comments_init extends Migration
{
    public function safeUp()
    {

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'entity' => $this->char(20)->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'parent_id' => $this->integer()->null(),
            'level' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'related_to' => $this->string(500)->notNull(),
            'url' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-comment-entity', '{{%comment}}', 'entity');
        $this->createIndex('idx-comment-status', '{{%comment}}', 'status');

        $this->addForeignKey('{{%comment_created_by}}', '{{%comment}}', 'created_by', '{{%user}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}
