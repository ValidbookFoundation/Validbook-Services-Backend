<?php

use yii\db\Migration;

/**
 * Class m180202_094151_drop_table_user_auth
 */
class m180202_094151_drop_table_user_auth extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropTable('user_auth');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180202_094151_drop_table_user_auth cannot be reverted.\n";

        return false;
    }
}
