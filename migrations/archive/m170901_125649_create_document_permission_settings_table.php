<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document_permission_settings`.
 */
class m170901_125649_create_document_permission_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('document_permission_settings', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
            'permission_state' => $this->smallInteger()->notNull(),
            'custom_permission_id' => $this->integer()->defaultValue(null)
        ]);

        $this->createIndex('doc_id_index', 'document_permission_settings', 'doc_id');
        $this->createIndex('doc_permission_id_index', 'document_permission_settings', 'permission_id');
        $this->createIndex('doc_custom_id_index', 'document_permission_settings', 'custom_permission_id');

        $this->createTable('document_custom_permissions', [
            'id' => $this->primaryKey(),
            'custom_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('dim_permission_document', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);

        $this->insert('dim_permission_document', ['name' => 'can_see_content']);
        $this->insert('dim_permission_document', ['name' => 'can_sign']);


        $this->addForeignKey('doc_fk_id', 'document_permission_settings', 'doc_id', '{{%document}}', 'id');
        $this->addForeignKey('doc_permission_fk_id', 'document_permission_settings', 'permission_id', '{{%dim_permission_document}}', 'id');
        $this->addForeignKey('doc_custom_user_fk_id', 'document_custom_permissions', 'user_id', '{{%user}}', 'id');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('doc_id_index', 'document_permission_settings');
        $this->dropIndex('doc_permission_id_index', 'document_permission_settings');
        $this->dropIndex('doc_custom_id_index', 'document_permission_settings');

        $this->dropForeignKey('doc_fk_id', 'document_permission_settings');
        $this->dropForeignKey('doc_permission_fk_id', 'document_permission_settings');
        $this->dropForeignKey('doc_custom_user_fk_id', 'document_custom_permissions');


        $this->dropTable('document_permission_settings');
        $this->dropTable('dim_permission_document');
        $this->dropTable('document_custom_permissions');
    }
}
