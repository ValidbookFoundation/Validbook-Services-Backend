<?php

use yii\db\Migration;

/**
 * Handles dropping box_id from table `box`.
 */
class m180202_101422_drop_box_id_column_from_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('card', 'box_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
