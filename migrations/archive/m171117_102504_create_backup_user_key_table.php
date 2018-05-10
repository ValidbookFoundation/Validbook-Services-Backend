<?php

use yii\db\Migration;

/**
 * Handles the creation of table `backup_user_key`.
 */
class m171117_102504_create_backup_user_key_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('backup_user_key', [
            'id' => $this->primaryKey(),
            'public_address' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('backup_user_key');
    }
}
