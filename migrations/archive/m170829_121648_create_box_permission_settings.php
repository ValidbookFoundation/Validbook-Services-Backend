<?php

use yii\db\Migration;

class m170829_121648_create_box_permission_settings extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('dim_permission_box', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()
        ]);
        $this->batchInsert('dim_permission_box', ['name'], [['can_see_exists'], ['can_see_content'], ['can_add_documents'], ['can_delete_documents']]);

        $this->createTable('box_permission_settings', [
            'id' => $this->primaryKey(),
            'box_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
            'permission_state' => $this->smallInteger()->notNull(),
            'custom_permission_id' => $this->integer()->defaultValue(null)
        ]);

        $this->createIndex('box_id_index', 'box_permission_settings', 'box_id');
        $this->createIndex('permission_box_id_index', 'box_permission_settings', 'permission_id');
        $this->createIndex('custom_box_id_index', 'box_permission_settings', 'custom_permission_id');

        $this->createTable('box_custom_permissions', [
            'id' => $this->primaryKey(),
            'custom_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('box_fk_id', 'box_permission_settings', 'box_id', '{{%box}}', 'id');
        $this->addForeignKey('permission_box_fk_id', 'box_permission_settings', 'permission_id', '{{%dim_permission_box}}', 'id');
        $this->addForeignKey('custom_box_user_fk_id', 'box_custom_permissions', 'user_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropIndex('box_id_index', 'box_permission_settings');
        $this->dropIndex('permission_box_id_index', 'box_permission_settings');
        $this->dropIndex('custom_box_id_index', 'box_permission_settings');

        $this->dropForeignKey('{{%box_fk_id}', '{{%box_permission_settings}}');
        $this->dropForeignKey('{{%permission_box_fk_id}', '{{%box_permission_settings}}');
        $this->dropForeignKey('{{%custom_box_user_fk_id}', '{{%box_permission_settings}}');

        $this->dropTable('box_permission_settings');
        $this->dropTable('box_custom_permissions');
        $this->dropTable('dim_permission_box');
    }
}
