<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dim_permission_story`.
 */
class m170804_124459_create_dim_permission_story_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('dim_permission_story', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);

        $this->insert('dim_permission_story', ['name' => 'can_see_content']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('dim_permission_story');
    }
}
