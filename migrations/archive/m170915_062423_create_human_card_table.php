<?php

use yii\db\Migration;

/**
 * Handles the creation of table `human_card`.
 */
class m170915_062423_create_human_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('human_card', [
            'id' => $this->primaryKey(),
            'box_id' => $this->integer()->notNull(),
            'public_address' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'is_valid' => $this->boolean()->defaultValue(false),
            'valid_start_date' => $this->integer()->defaultValue(null),
            'valid_end_date' => $this->integer()->defaultValue(null),

        ]);

        $this->createIndex('{{%hc_box_address}}', '{{%human_card}}', 'box_id, public_address', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('{{%hc_box_address}}', '{{%human_card}}');
        $this->dropTable('human_card');
    }
}
