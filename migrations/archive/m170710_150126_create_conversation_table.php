<?php

use yii\db\Migration;

/**
 * Handles the creation of table `conversation`.
 */
class m170710_150126_create_conversation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('conversation', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->defaultValue(null),
            'is_group' => $this->boolean()->defaultValue(false),
            'count_users' => $this->smallInteger()->defaultValue(2)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('conversation');
    }
}
