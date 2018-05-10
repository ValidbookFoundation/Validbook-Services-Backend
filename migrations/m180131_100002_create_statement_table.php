<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statement`.
 */
class m180131_100002_create_statement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('statement_template', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'sort' => $this->integer(11)->defaultValue(0)
        ]);

        $this->createTable('statement', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'template_id' => $this->integer(11),
            'is_signed' => $this->smallInteger(1),
            'url' => $this->string(255),
            'nonce' => $this->string(50),
            'created_at' => $this->integer(11)
        ]);

        $this->addForeignKey(
            'fk-statement-statement_template_id',
            'statement',
            'template_id',
            '{{%statement_template}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-statement-user_id',
            'statement',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('statement_template', ['title'], [
            ['Signed Document'],
            ['Certificate (generic)'],
            ['Certificate (Open Badge Standard)']
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-statement-statement_template_id', 'statement');
        $this->dropForeignKey('fk-statement-user_id', 'statement');
        $this->dropTable('statement');
        $this->dropTable('statement_template');
    }
}
