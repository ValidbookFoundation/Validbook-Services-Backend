<?php

use yii\db\Migration;

/**
 * Handles the creation of table `claim`.
 */
class m171117_150537_create_dim_claim_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('dim_claim_card', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull()
        ]);

        $this->insert('dim_claim_card', ['type' => 'I am human']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('dim_claim_card');
    }
}
