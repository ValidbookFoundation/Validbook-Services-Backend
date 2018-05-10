<?php

use yii\db\Migration;

/**
 * Class m180202_090049_drop_column_public_address
 */
class m180202_090049_drop_column_public_address extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('user', 'public_address');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('user', 'public_address', $this->string(255)->null());
    }
}
