<?php

use yii\db\Migration;

/**
 * Handles the creation of table `card_claim`.
 */
class m171117_151051_create_card_claim_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('card_claim', [
            'id' => $this->primaryKey(),
            'claim_id' => $this->integer()->notNull(),
            'card_address' => $this->string()->notNull(),
            'sig_address' => $this->string()->notNull(),
            'hash' => $this->text()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('card_claim');
    }
}
