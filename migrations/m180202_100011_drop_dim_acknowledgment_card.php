<?php

use yii\db\Migration;

/**
 * Class m180202_100011_drop_dim_acknowledgment_card
 */
class m180202_100011_drop_dim_acknowledgment_card extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropTable('dim_acknowledgment_card');
        $this->dropColumn('card_acknowledgment', 'acknow_id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180202_100011_drop_dim_acknowledgment_card cannot be reverted.\n";

        return false;
    }
}
