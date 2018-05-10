<?php

use yii\db\Migration;

/**
 * Handles the creation of table `kds_with_drawal`.
 */
class m171010_144846_create_kds_with_drawal_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('kds_with_drawal_request_from_custodial_account', [
            'id' => $this->primaryKey(),
            'hc_address' => $this->string()->notNull(),
            'kds_amount' => $this->bigInteger()->notNull(),
            'timestamp' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('kds_with_drawal_request_from_custodial_account');
    }
}
