<?php

use yii\db\Migration;

class m170731_062519_create_table_dim_permission_book extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('dim_permission_book', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()
        ]);
        $this->batchInsert('dim_permission_book', ['name'], [['can_see_exists'], ['can_see_content'], ['can_add_stories'], ['can_delete_stories'], ['can_manage_settings']]);
    }

    public function down()
    {
        $this->dropTable('dim_permission_book');
    }

}
