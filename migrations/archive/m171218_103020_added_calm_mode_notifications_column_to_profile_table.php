<?php

use yii\db\Migration;

/**
 * Class m171218_103020_added_calm_mode_notifications_column_to_profile_table
 */
class m171218_103020_added_calm_mode_notifications_column_to_profile_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
       $this->addColumn('profile', 'calm_mode_notifications', $this->boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('profile', 'calm_mode_notifications');
    }
}
