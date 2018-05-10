<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_key`.
 */
class m171116_110233_create_user_key_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_key', [
            'id' => $this->primaryKey(),
            'public_address' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'random_number' => $this->integer(10)->defaultValue(null)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_key');
    }
}
