<?php

use yii\db\Migration;

/**
 * Handles the creation of table `human_card`.
 */
class m170914_065152_create_draft_human_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('draft_human_card', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->notNull(),
            'public_address' => $this->string()->defaultValue(null),
            'message' => $this->text()->defaultValue(null),
            'hash' => $this->text()->defaultValue(null),
            'user_id' => $this->integer()->notNull(),
            'box_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->createIndex('{{%un_draft_hc}}', '{{%draft_human_card}}', 'public_address, user_id, box_id', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('{{%un_draft_hc}}', '{{%draft_human_card}}');

        $this->dropTable('draft_human_card');
    }
}
