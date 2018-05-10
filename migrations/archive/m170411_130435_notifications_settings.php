<?php

use yii\db\Migration;

class m170411_130435_notifications_settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%notification_settings}}', [
            'user_id' => $this->primaryKey(),
            'settings' => $this->text()->notNull(),

        ]);
        $this->addForeignKey('{{%notification_settings_user_id}}', '{{%notification_settings}}', 'user_id', '{{%user}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%notification_settings}}');
    }
}
