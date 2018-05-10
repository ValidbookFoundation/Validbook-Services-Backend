<?php

use yii\db\Migration;

/**
 * Handles the creation of table `kds_transaction_records`.
 */
class m171115_101607_create_kds_transaction_records_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('kds_transaction_records', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger(),
            'hc_address' => $this->string()->notNull(),
            'kds_amount' => $this->bigInteger()->notNull(),
            'timestamp' => $this->integer()->notNull()

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('kds_transaction_records');
    }
}
