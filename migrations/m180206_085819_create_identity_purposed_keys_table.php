<?php

use yii\db\Migration;

/**
 * Handles the creation of table `identity_purposed_keys`.
 */
class m180206_085819_create_identity_purposed_keys_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('identity_purposed_keys', [
            'id' => $this->primaryKey(),
            'identity' => $this->string()->notNull(),
            'public_address' => $this->string()->null(),
            'purpose' => $this->text()->null(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull()
        ]);

        $this->renameColumn('identity', 'backup_address', 'recovery_address');
        $this->renameColumn('identity_keys_history', 'backup_address', 'recovery_address');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('identity_purposed_keys');
        $this->renameColumn('identity', 'recovery_address', 'backup_address');
        $this->renameColumn('identity_keys_history', 'recovery_address', 'backup_address');
    }
}
