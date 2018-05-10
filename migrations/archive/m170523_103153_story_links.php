<?php

use yii\db\Migration;

class m170523_103153_story_links extends Migration
{
    public function up()
    {
        $this->createTable('{{%story_links}}', [
            'id' => $this->primaryKey(),
            'story_id' => $this->integer()->notNull(),
            'video_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull(),
            'link' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'twitter_author' => $this->string(255)->notNull(),
            'twitter_avatar' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('{{%story_links_story_id}}', '{{%story_links}}', 'story_id', '{{%story}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%story_links}}');
    }

}
