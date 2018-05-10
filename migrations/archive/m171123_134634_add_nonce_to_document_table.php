<?php

use yii\db\Migration;

/**
 * Class m171123_134634_add_nonce_to_document_table
 */
class m171123_134634_add_nonce_to_document_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('document', 'nonce', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('document', 'nonce');
    }
}
