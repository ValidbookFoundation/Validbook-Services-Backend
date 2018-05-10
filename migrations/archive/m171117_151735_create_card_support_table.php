<?php

use yii\db\Migration;

/**
 * Handles the creation of table `card_support`.
 */
class m171117_151735_create_card_support_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('card_support', [
            'id' => $this->primaryKey(),
            'support_id' => $this->integer(),
            'card_address' => $this->string()->notNull(),
            'sig_address' => $this->string()->notNull(),
            'hash' => $this->text()->defaultValue(null)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('card_support');
    }
}
