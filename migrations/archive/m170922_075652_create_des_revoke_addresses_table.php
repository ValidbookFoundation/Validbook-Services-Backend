<?php

use yii\db\Migration;

/**
 * Handles the creation of table `des_revoke_addresses`.
 */
class m170922_075652_create_des_revoke_addresses_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('des_revoke_addresses', [
            'id' => $this->primaryKey(),
            'human_card_id' => $this->integer()->notNull(),
            'address' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('des_revoke_addresses');
    }
}
