<?php

use yii\db\Migration;

/**
 * Class m171120_124108_modify_digital_property_table
 */
class m171120_124108_modify_digital_property_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
       $this->addColumn('h_card_digital_property', 'card_address', $this->string()->notNull());
       $this->dropColumn('h_card_digital_property', 'human_card_id');
    }

    public function down()
    {
        echo "m171120_124108_modify_digital_property_table cannot be reverted.\n";

        return false;
    }

}
