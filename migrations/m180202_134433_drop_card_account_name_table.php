<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `card_account_name`.
 */
class m180202_134433_drop_card_account_name_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('card_account_name');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        return false;
    }
}
