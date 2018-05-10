<?php

use yii\db\Migration;

/**
 * Handles the creation of table `story_permission_settings`.
 */
class m170804_125044_create_story_permission_settings_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('story_permission_settings', [
            'id' => $this->primaryKey(),
            'story_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
            'permission_state' => $this->smallInteger()->notNull(),
            'custom_permission_id' => $this->integer()->defaultValue(null)
        ]);

        $this->createIndex('story_id_index', 'story_permission_settings', 'story_id');
        $this->createIndex('permission_id_index', 'story_permission_settings', 'permission_id');
        $this->createIndex('custom_id_index', 'story_permission_settings', 'custom_permission_id');

        $this->createTable('story_custom_permissions', [
            'id' => $this->primaryKey(),
            'custom_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('story_fk_id', 'story_permission_settings', 'story_id', '{{%story}}', 'id');
        $this->addForeignKey('story_permission_fk_id', 'story_permission_settings', 'permission_id', '{{%dim_permission_story}}', 'id');
        $this->addForeignKey('story_custom_user_fk_id', 'story_custom_permissions', 'user_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropIndex('story_id_index', 'story_permission_settings');
        $this->dropIndex('permission_id_index', 'story_permission_settings');
        $this->dropIndex('custom_id_index', 'story_permission_settings');

        $this->dropForeignKey('{{%story_fk_id}', '{{%story_permission_settings}}');
        $this->dropForeignKey('{{%story_permission_fk_id}', '{{%story_permission_settings}}');
        $this->dropForeignKey('{{%story_custom_user_fk_id}', '{{%story_custom_permissions}}');

        $this->dropTable('story_permission_settings');
        $this->dropTable('story_custom_permissions');
    }

}
