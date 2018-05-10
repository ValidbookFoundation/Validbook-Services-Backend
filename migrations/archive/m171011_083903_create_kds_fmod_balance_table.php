<?php

use yii\db\Migration;

/**
 * Handles the creation of table `kds_fmod_balance`.
 */
class m171011_083903_create_kds_fmod_balance_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('kds_fmod_balance', [
            'id' => $this->primaryKey(),
            "kds_fmod" => $this->bigInteger()->defaultValue(0),
            "timestamp" => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('kds_fmod_balance');
    }
}
