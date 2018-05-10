<?php

use yii\db\Migration;

/**
 * Handles the creation of table `story_files`.
 */
class m170907_075556_create_story_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('story_files', [
            'id' => $this->primaryKey(),
            'story_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'etag' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('story_files');
    }
}
