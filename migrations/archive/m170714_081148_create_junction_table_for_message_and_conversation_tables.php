<?php

use yii\db\Migration;

/**
 * Handles the creation of table `conversation_to_message_user`.
 * Has foreign keys to the tables:
 *
 * - `message`
 * - `conversation`
 */
class m170714_081148_create_junction_table_for_message_and_conversation_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('conversation_to_message_user', [
            'id' => $this->primaryKey(),
            'conversation_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'is_new' => $this->boolean()->defaultValue(true),
            'is_seen' => $this->boolean()->defaultValue(false),
            'is_left' => $this->boolean()->defaultValue(false),
        ]);

        // add foreign key for table `conversation`
        $this->addForeignKey(
            'fk-message_conversation-conversation_id',
            'conversation_to_message_user',
            'conversation_id',
            'conversation',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `conversation`
        $this->dropForeignKey(
            'fk-message_conversation-conversation_id',
            'conversation_to_message_user'
        );

        $this->dropTable('conversation_to_message_user');
    }
}
