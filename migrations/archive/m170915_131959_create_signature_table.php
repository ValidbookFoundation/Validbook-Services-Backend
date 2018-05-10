<?php

use yii\db\Migration;

/**
 * Handles the creation of table `signature`.
 */
class m170915_131959_create_signature_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('signature', [
            'id' => $this->primaryKey(),
            'public_address' => $this->string()->notNull(),
            'sig' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'hash' => $this->text()->notNull(),
            'short_sig_link' => $this->string()->notNull(),
            'long_sig_link' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'is_voided' => $this->boolean()->defaultValue(false)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('signature');
    }

}
