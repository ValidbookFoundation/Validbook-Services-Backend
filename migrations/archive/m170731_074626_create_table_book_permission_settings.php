<?php

use yii\db\Migration;

class m170731_074626_create_table_book_permission_settings extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('book_permission_settings', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
            'permission_state' => $this->smallInteger()->notNull(),
            'custom_permission_id' => $this->integer()->defaultValue(null)
        ]);

        $this->createIndex('book_id_index', 'book_permission_settings', 'book_id');
        $this->createIndex('permission_id_index', 'book_permission_settings', 'permission_id');
        $this->createIndex('custom_id_index', 'book_permission_settings', 'custom_permission_id');

        $this->createTable('book_custom_permissions', [
            'id' => $this->primaryKey(),
            'custom_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('book_fk_id', 'book_permission_settings', 'book_id', '{{%book}}', 'id');
        $this->addForeignKey('permission_fk_id', 'book_permission_settings', 'permission_id', '{{%dim_permission_book}}', 'id');
        $this->addForeignKey('custom_user_fk_id', 'book_custom_permissions', 'user_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropIndex('book_id_index', 'book_permission_settings');
        $this->dropIndex('permission_id_index', 'book_permission_settings');
        $this->dropIndex('custom_id_index', 'book_permission_settings');

        $this->dropForeignKey('{{%book_fk_id}', '{{%book_permission_settings}}');
        $this->dropForeignKey('{{%permission_fk_id}', '{{%book_permission_settings}}');
        $this->dropForeignKey('{{%custom_permission_fk_id}', '{{%book_permission_settings}}');
        $this->dropForeignKey('{{%custom_user_fk_id}', '{{%book_custom_permissions}}');

        $this->dropTable('book_permission_settings');
        $this->dropTable('book_custom_permissions');
    }

}
