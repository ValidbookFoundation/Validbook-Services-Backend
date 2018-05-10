<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document`.
 */
class m170901_124241_create_document_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type' => $this->smallInteger()->defaultValue(0),
            'user_id' => $this->integer()->notNull(),
            'box_id' => $this->integer()->notNull(),
            'url' => $this->string()->notNull(),
            'hash' => $this->text()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'is_signed' => $this->boolean()->defaultValue(false),
            'is_encrypted' => $this->boolean()->defaultValue(false),
            'is_moved_to_bin' => $this->boolean()->defaultValue(false),
            'is_open_for_sig' => $this->boolean()->defaultValue(false),
        ]);

        $this->addForeignKey('doc_user_fk_id', 'document', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('doc_box_fk_id', 'document', 'box_id', '{{%box}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('document');
    }
}
