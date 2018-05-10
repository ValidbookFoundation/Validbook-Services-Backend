<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dim_acknowledgment_card`.
 */
class m171117_152218_create_dim_acknowledgment_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('dim_acknowledgment_card', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull()
        ]);

        $this->insert('dim_acknowledgment_card', ['type' => 'Virtual entity rights']);
        $this->insert('dim_acknowledgment_card', ['type' => 'You are human']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('dim_acknowledgment_card');
    }
}
