<?php

use yii\db\Migration;

/**
 * Class m171219_142622_add_column_size_story_files_table
 */
class m171219_142622_photo_thumbnails extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('cover_size', 'original_id', $this->integer()->defaultValue(null)->after('url'));
        $this->addColumn('avatar_size', 'original_id', $this->integer()->defaultValue(null)->after('url'));

        $this->createTable('story_image_size', [
            'id' => $this->primaryKey(),
            'original_id' => $this->integer()->defaultValue(null),
            'model_id' => $this->integer()->notNull(),
            'type' => $this->string(25)->notNull(),
            'size' => $this->string(100)->notNull(),
            'url' => $this->string(255)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropColumn('cover_size', 'original_id');
        $this->dropColumn('avatar_size', 'original_id');
        $this->dropTable('story_image_size');
    }

}
