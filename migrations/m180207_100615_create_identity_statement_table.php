<?php

use yii\db\Migration;

/**
 * Handles the creation of table `identity_statement`.
 */
class m180207_100615_create_identity_statement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('identity_statement', [
            'id' => $this->primaryKey(),
            'identity' => $this->string(255)->notNull(),
            'statement_url' => $this->string(255)->notNull(),
            'title' => $this->string(255)->null(),
            'hash' => $this->text()->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'is_revoked' => $this->boolean()->defaultValue(false),
            'uuid' => $this->string(255)->notNull()
        ]);

        // creates index for column `identity` for table identity
        $this->createIndex(
            'idx-identity-identity-unique',
            'identity',
            'identity',
            true
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-identity_statement-identity-identity',
            'identity_statement',
            'identity',
            'identity',
            'identity',
            'CASCADE'
        );

        $this->addColumn('identity', 'display_name', $this->string(255)->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('identity', 'display_name');
        $this->dropForeignKey('fk-identity_statement-identity-identity', 'identity_statement');
        $this->dropIndex('idx-identity-unique','identity');
        $this->dropTable('identity_statement');
    }
}
