<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_photo`.
 */
class m170911_114926_create_user_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_photo', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'url' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_photo');
    }
}
