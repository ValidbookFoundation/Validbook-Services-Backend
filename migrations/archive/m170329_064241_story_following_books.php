<?php

use yii\db\Migration;

class m170329_064241_story_following_books extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%story}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'in_storyline' => $this->smallInteger()->notNull()->defaultValue(1),
            'in_channels' => $this->smallInteger()->notNull()->defaultValue(1),
            'in_book' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'started_on' => $this->integer()->notNull(),
            'completed_on' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('{{%story_user_id}}', '{{%story}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%story_book}}', [
            'id' => $this->primaryKey(),
            'story_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'is_pin' => $this->smallInteger()->notNull()->defaultValue(0),
            'pin_order' => $this->integer()->defaultValue(null),
            'is_moved_to_bin' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey('{{%story_book_story_id}}', '{{%story_book}}', 'story_id', '{{%story}}', 'id');
        $this->addForeignKey('{{%story_book_book_id}}', '{{%story_book}}', 'book_id', '{{%book}}', 'id');

        $this->createTable('{{%channel}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'is_default' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('{{%channel_user_id}}', '{{%channel}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%follow}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'channel_id' => $this->integer()->notNull(),
            'followee_id' => $this->integer()->notNull(),
            'is_follow' => $this->smallInteger()->defaultValue(null),
            'is_block' => $this->smallInteger()->defaultValue(null),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('{{%follow_user_id}}', '{{%follow}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%follow_followee_id}}', '{{%follow}}', 'followee_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%follow_channel_id}}', '{{%follow}}', 'channel_id', '{{%channel}}', 'id');

        $this->createTable('{{%follow_book}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'channel_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'is_follow' => $this->smallInteger()->defaultValue(null),
            'is_block' => $this->smallInteger()->defaultValue(null),
            'created_at' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('{{%follow_book_user_id}}', '{{%follow_book}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%follow_book_book_id}}', '{{%follow_book}}', 'book_id', '{{%book}}', 'id');
        $this->addForeignKey('{{%follow_book_channel_id}}', '{{%follow_book}}', 'channel_id', '{{%channel}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('story');
        $this->dropTable('story_book');
        $this->dropTable('follow');
        $this->dropTable('follow_book');
        $this->dropTable('channel');
    }

}
