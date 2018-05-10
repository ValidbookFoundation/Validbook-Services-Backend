<?php

use yii\db\Migration;

/**
 * Class m171220_144512_add_column_delete_hours_messages_conversation_table
 */
class m171220_144512_add_column_delete_hours_messages_conversation_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
      $this->addColumn('conversation', 'hours_delete_messages', $this->integer()->defaultValue(24)->after('count_users'));
    }

    public function down()
    {
        $this->dropColumn('conversation', 'hours_delete_messages');
    }

}
