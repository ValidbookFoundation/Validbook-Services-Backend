<?php

use yii\db\Migration;

/**
 * Handles the creation of table `support_identity_statement`.
 */
class m180208_104706_create_link_identity_statement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('link_identity_statement', [
            'id' => $this->primaryKey(),
            'owner_identity' => $this->string(255),
            'identity_statement_uuid' => $this->string(255),
            'identity_statement_id' => $this->integer(11),
            'url' => $this->string(255),
            'hash' => $this->text(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('support_identity_statement');
    }
}
