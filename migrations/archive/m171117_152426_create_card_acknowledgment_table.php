<?php

use yii\db\Migration;

/**
 * Handles the creation of table `card_acknowledgment`.
 */
class m171117_152426_create_card_acknowledgment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('card_acknowledgment', [
            'id' => $this->primaryKey(),
            'acknow_id' => $this->integer()->notNull(),
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
        $this->dropTable('card_acknowledgment');
    }
}
