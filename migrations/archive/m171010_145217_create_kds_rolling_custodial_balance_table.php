<?php

use yii\db\Migration;

/**
 * Handles the creation of table `kds_rolling_custodial_balance`.
 */
class m171010_145217_create_kds_rolling_custodial_balance_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('kds_rolling_custodial_balance', [
            'id' => $this->primaryKey(),
            'hc_address' => $this->string()->notNull(),
            'balance' => $this->bigInteger()->notNull(),
            'timestamp_of_calc' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('kds_rolling_custodial_balance');
    }
}
