<?php

use yii\db\Migration;

/**
 * Handles the creation of table `kds_incoming`.
 */
class m171010_144157_create_kds_incoming_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('kds_origin_to_vhc_daily_incoming_custodial_records', [
            'id' => $this->primaryKey(),
            'vhc_address' => $this->string()->notNull(),
            'kds_amount' => $this->bigInteger()->notNull(),
            'timestamp' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('kds_origin_to_vhc_daily_incoming_custodial_records');
    }
}
