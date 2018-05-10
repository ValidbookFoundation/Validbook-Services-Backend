<?php

use yii\db\Migration;

/**
 * Handles the creation of table `deactivate_user`.
 */
class m170929_121337_create_deactivate_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('deactivate_user', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'time_expired' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('deactivate_user');
    }
}
