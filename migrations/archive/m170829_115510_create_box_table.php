<?php

use yii\db\Migration;

/**
 * Handles the creation of table `box`.
 */
class m170829_115510_create_box_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%box}}', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_root' => $this->smallInteger()->defaultValue(0),
            'is_default' => $this->integer()->defaultValue(0),
            'description' => $this->string()->notNull(),
            'cover' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_moved_to_bin' => $this->smallInteger()->defaultValue(0)
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('box');
    }
}
