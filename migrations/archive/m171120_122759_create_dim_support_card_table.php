<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dim_support_card`.
 */
class m171120_122759_create_dim_support_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('dim_support_card', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull()
        ]);

        $this->insert('dim_support_card', ['type' => 'You are human']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('dim_support_card');
    }
}
