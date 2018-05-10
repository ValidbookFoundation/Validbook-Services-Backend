<?php

use yii\db\Migration;

/**
 * Class m180205_085535_add_backup_address_to_identity_keys
 */
class m180205_085535_add_backup_address_to_identity_keys extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('identity', 'public_address', $this->string(255)->notNull());
        $this->addColumn('identity', 'backup_address', $this->string(255)->notNull());

        $this->createTable('identity_keys_history', [
            'id' => $this->primaryKey(),
            'identity_id' => $this->integer()->notNull(),
            'public_address' => $this->string(255)->notNull(),
            'backup_address' => $this->string(255)->notNull(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(11),
        ]);

        $this->dropTable('identity_keys');
        //$this->dropTable('user_key');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('identity', 'public_address');
        $this->dropColumn('identity', 'backup_address');

        $this->dropTable('identity_keys_history');
    }
}
