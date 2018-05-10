<?php

use yii\db\Migration;

/**
 * Class m180201_152044_add_column_will_be_json_changed_to_statement_template
 */
class m180201_152044_add_column_will_be_json_changed_to_statement_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('statement_template', 'will_be_json_changed', $this->smallInteger(1)->after('json')->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('statement_template', 'will_be_json_changed');
    }
}
