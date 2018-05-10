<?php

use yii\db\Migration;

/**
 * Class m180201_141857_add_column_json_to_statement_template
 */
class m180201_141857_add_column_json_to_statement_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('statement_template', 'json', $this->text()->after('title'));
        $this->update('statement_template', [
            'json' => '{"id":"","type":["Credential","Certificate"],"issued":"","presentationTemplate":"https://validbook.org/certificate-green-nice-template","claim":{"name":"...Certificate...","description":"For successful completion of the...","recipient":"did:vb:recipient_did"}}'
        ], ['!=', 'id', 1]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('statement_template', 'json');
    }
}
