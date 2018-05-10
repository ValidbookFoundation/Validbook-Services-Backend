<?php

use yii\db\Migration;

class m171108_092941_add_column_content_document_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn("document", "content", $this->text()->defaultValue(null)->after('url'));
    }

    public function down()
    {
        echo "m171108_092941_add_column_content_document_table cannot be reverted.\n";

        return false;
    }

}
