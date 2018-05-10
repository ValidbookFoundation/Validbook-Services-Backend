<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document_file`.
 */
class m170928_083608_create_document_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('document_file', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'hash' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('document_file');
    }
}
