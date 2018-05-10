<?php

use yii\db\Migration;

/**
 * Handles the creation of table `identity`.
 */
class m180202_141408_create_identity_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('identity', [
            'id' => $this->primaryKey(),
            'identity' => $this->string(255)->notNull(),
            'fullName' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull(),
            'hash' => $this->text()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'is_valid' => $this->boolean()->defaultValue(false),
            'is_signed' => $this->boolean()->defaultValue(false),
            'valid_start_date' => $this->integer()->defaultValue(null),
            'valid_end_date' => $this->integer()->defaultValue(null)
        ]);

        $this->createTable('identity_keys', [
            'id' => $this->primaryKey(),
            'identity_id' => $this->integer()->notNull(),
            'public_address' => $this->string(255)->notNull(),
            'is_main' => $this->boolean()->defaultValue(false),
            'is_revoked' => $this->boolean()->defaultValue(false),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('identity_keys');
        $this->dropTable('identity');
    }
}
