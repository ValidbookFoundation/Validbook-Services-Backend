<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_email`.
 */
class m171207_073332_create_user_email_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_email', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'email' => $this->string()->notNull(),
            'type' => $this->smallInteger()->defaultValue(1),
            'active' => $this->boolean()->defaultValue(true)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_email');
    }
}
