<?php

use yii\db\Migration;

/**
 * Handles the creation of table `card`.
 */
class m171116_150239_create_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('card', [
            'id' => $this->primaryKey(),
            'box_id' => $this->integer()->notNull(),
            'public_address' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'hash' => $this->text()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'is_valid' => $this->boolean()->defaultValue(false),
            'valid_start_date' => $this->integer()->defaultValue(null),
            'valid_end_date' => $this->integer()->defaultValue(null),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('card');
    }
}
