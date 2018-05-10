<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document_encrtyped_receivers`.
 */
class m171005_083847_create_document_encrtyped_receivers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('document_encrypted', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'receiver_public_address' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('document_encrypted');
    }
}
