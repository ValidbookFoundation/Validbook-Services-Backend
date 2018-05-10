<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m170327_133617_create_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'is_root' => $this->smallInteger()->defaultValue(0),
            'is_default' => $this->smallInteger()->defaultValue(0),
            'description' => $this->text()->notNull(),
            'cover' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'auto_import' => $this->smallInteger()->notNull()->defaultValue(1),
            'auto_export' => $this->smallInteger()->notNull()->defaultValue(1),
            'is_moved_to_bin' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey('{{%book_author_id}}', '{{%book}}', 'author_id', '{{%user}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('book');
    }
}
