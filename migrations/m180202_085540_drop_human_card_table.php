<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `human_card`.
 */
class m180202_085540_drop_human_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('human_card');
    }

    /**
     * @inheritdoc
     */
    public function down()
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
    }
}
