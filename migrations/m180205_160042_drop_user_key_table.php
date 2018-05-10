<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `user_key`.
 */
class m180205_160042_drop_user_key_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('user_key');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        return false;
    }
}
