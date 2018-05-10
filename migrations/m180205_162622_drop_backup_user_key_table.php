<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `backup_user_key`.
 */
class m180205_162622_drop_backup_user_key_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('backup_user_key');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        return false;
    }
}
