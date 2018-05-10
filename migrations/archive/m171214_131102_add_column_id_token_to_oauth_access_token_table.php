<?php

use yii\db\Migration;

/**
 * Class m171214_131102_add_column_id_token_to_oauth_access_token_table
 */
class m171214_131102_add_column_id_token_to_oauth_access_token_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
       $this->addColumn('oauth_access_tokens', 'id_token', $this->string(1000)->defaultValue(null)->after('access_token'));
        $this->addColumn('oauth_authorization_codes', 'state', $this->string()->defaultValue(null)->after('scope'));
        $this->addColumn('oauth_authorization_codes', 'nonce', $this->string()->defaultValue(null)->after('state'));
    }

    public function down()
    {
        echo "m171214_131102_add_column_id_token_to_oauth_access_token_table cannot be reverted.\n";

        return false;
    }

}
