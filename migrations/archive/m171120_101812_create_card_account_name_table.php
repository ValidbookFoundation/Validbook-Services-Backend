<?php

use yii\db\Migration;

/**
 * Handles the creation of table `card_account_name`.
 */
class m171120_101812_create_card_account_name_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('card_account_name', [
            'id' => $this->primaryKey(),
            'number' => $this->integer()->defaultValue(0),
            'card_address' => $this->string()->notNull(),
            'sig_address' => $this->string()->defaultValue(null),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'hash' => $this->text()->defaultValue(null),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('card_account_name');
    }
}
