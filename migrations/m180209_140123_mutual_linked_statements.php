<?php

use yii\db\Migration;

/**
 * Class m180209_140123_mutual_linked_statements
 */
class m180209_140123_mutual_linked_statements extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        //$this->dropTable('statement');

        $this->addColumn(
            'identity_statement',
            'type',
            $this->integer(11)->null()->after('statement_url')
        );

        $this->addColumn(
            'identity_statement',
            'identity_id',
            $this->integer(11)->null()->after('identity')
        );

        $this->addColumn(
            'link_identity_statement',
            'is_ignored',
            $this->smallInteger(1)->defaultValue(0)
        );

        $this->addColumn(
            'link_identity_statement',
            'identity_id',
            $this->smallInteger(1)->notNull()->after('owner_identity')
        );

        $this->createTable('mutual_linking', [
            'id' => $this->primaryKey(),
            'identity1_id' => $this->integer(11),
            'identity2_id' => $this->integer(11)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('link_identity_statement', 'identity_id');
        $this->dropColumn('identity_statement', 'identity_id');
        $this->dropColumn('identity_statement', 'type');
        $this->dropTable('mutual_linked_statements');
    }
}
