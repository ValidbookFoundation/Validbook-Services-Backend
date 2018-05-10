<?php

use yii\db\Migration;

class m170607_144251_avatar_size extends Migration
{
    public function up()
    {
        $this->createTable('{{%avatar_size}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'size' => $this->string(25)->notNull(),
            'url' => $this->string(255)->notNull()
        ]);

        $this->addForeignKey('{{%avatar_size_user_id}}', '{{%avatar_size}}', 'user_id', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%avatar_size}}');
    }


}
