<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statement_templates`.
 */
class m180201_102441_create_statement_templates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('statement_htmltemplate', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'link' => $this->string(255),
            'default' => $this->smallInteger(1)->defaultValue(0),
            'sort' => $this->integer(11)->defaultValue(0)
        ]);

        $this->batchInsert('statement_htmltemplate', ['title', 'link'], [
            ['Certificate - green', 'http://api-futurama1x.validbook.org/templates/1/template.html']
        ]);

        $this->createTable('statement_html_to_json', [
            'id' => $this->primaryKey(),
            'html_id' => $this->integer(11),
            'json_id' => $this->integer(11)
        ]);

        $this->addForeignKey(
            'fk-statement_html_to_json-statement_htmltemplate_id',
            'statement_html_to_json',
            'html_id',
            '{{%statement_htmltemplate}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-statement_html_to_json-statement_template_id',
            'statement_html_to_json',
            'json_id',
            '{{%statement_template}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('statement_html_to_json', ['html_id', 'json_id'], [
            [1, 2],
            [1, 3]
        ]);

        $this->addColumn('statement_template', 'default', $this->smallInteger(1)->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('statement_template', 'default');

        $this->dropForeignKey('fk-statement_htmltemplate_id', 'statement_html_to_json');
        $this->dropForeignKey('fk-statement_template_id', 'statement_html_to_json');

        $this->dropTable('statement_html_to_json');
        $this->dropTable('statement_htmltemplate');
    }
}
