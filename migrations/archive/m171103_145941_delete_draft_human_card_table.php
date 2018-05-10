<?php

use yii\db\Migration;

class m171103_145941_delete_draft_human_card_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropTable("draft_human_card");
    }

    public function down()
    {
        echo "m171103_145941_delete_draft_human_card_table cannot be reverted.\n";

        return false;
    }

}
