<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

/**
 * Class m171129_142622_create_oauth2_implementation
 */
class m171129_142622_create_oauth2_implementation extends Migration
{

    public function primaryKeyDefinition($columns) {
        return 'PRIMARY KEY (' . $this->db->getQueryBuilder()->buildColumns($columns) . ')';
    }

    public function foreignKeyDefinition($columns,$refTable,$refColumns,$onDelete = null,$onUpdate = null) {
        $builder = $this->db->getQueryBuilder();
        $sql = ' FOREIGN KEY (' . $builder->buildColumns($columns) . ')'
            . ' REFERENCES ' . $this->db->quoteTableName($refTable)
            . ' (' . $builder->buildColumns($refColumns) . ')';
        if ($onDelete !== null) {
            $sql .= ' ON DELETE ' . $onDelete;
        }
        if ($onUpdate !== null) {
            $sql .= ' ON UPDATE ' . $onUpdate;
        }
        return $sql;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%oauth_clients}}', [
            'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
            'client_secret' => Schema::TYPE_STRING . '(32) DEFAULT NULL',
            'redirect_uri' => Schema::TYPE_STRING . '(1000) NOT NULL',
            'grant_types' => Schema::TYPE_STRING . '(100) NOT NULL',
            'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
            $this->primaryKeyDefinition('client_id'),
        ], $tableOptions);

        $this->createTable('{{%oauth_access_tokens}}', [
            'access_token' => Schema::TYPE_STRING . '(40) NOT NULL',
            'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'expires' => Schema::TYPE_INTEGER . " NOT NULL",
            'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
            $this->primaryKeyDefinition('access_token'),
            $this->foreignKeyDefinition('client_id','{{%oauth_clients}}','client_id','CASCADE','CASCADE'),
        ], $tableOptions);

        $this->createTable('{{%oauth_refresh_tokens}}', [
            'refresh_token' => Schema::TYPE_STRING . '(40) NOT NULL',
            'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'expires' => Schema::TYPE_INTEGER . " NOT NULL",
            'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
            $this->primaryKeyDefinition('refresh_token'),
            $this->foreignKeyDefinition('client_id','{{%oauth_clients}}','client_id','CASCADE','CASCADE'),
        ], $tableOptions);

        $this->createTable('{{%oauth_authorization_codes}}', [
            'authorization_code' => Schema::TYPE_STRING . '(40) NOT NULL',
            'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'redirect_uri' => Schema::TYPE_STRING . '(1000) NOT NULL',
            'expires' => Schema::TYPE_INTEGER . " NOT NULL",
            'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
            $this->primaryKeyDefinition('authorization_code'),
            $this->foreignKeyDefinition('client_id','{{%oauth_clients}}','client_id','CASCADE','CASCADE'),
        ], $tableOptions);

        $this->createTable('{{%oauth_scopes}}', [
            'scope' => Schema::TYPE_STRING . '(2000) NOT NULL',
            'is_default' => Schema::TYPE_BOOLEAN . ' NOT NULL',
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%oauth_scopes}}');
        $this->dropTable('{{%oauth_authorization_codes}}');
        $this->dropTable('{{%oauth_refresh_tokens}}');
        $this->dropTable('{{%oauth_access_tokens}}');
        $this->dropTable('{{%oauth_clients}}');
    }

}
