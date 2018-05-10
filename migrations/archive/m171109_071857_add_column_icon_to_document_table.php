<?php

use yii\db\Migration;

class m171109_071857_add_column_icon_to_document_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
      $this->addColumn("document","icon", $this->string()->defaultValue(null)->after("content"));
    }

    public function down()
    {
        echo "m171109_071857_add_column_icon_to_document_table cannot be reverted.\n";

        return false;
    }

}
