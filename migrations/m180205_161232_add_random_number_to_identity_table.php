<?php

use yii\db\Migration;

/**
 * Class m180205_161232_add_random_number_to_identity_table
 */
class m180205_161232_add_random_number_to_identity_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('identity', 'random_number', $this->integer(10)->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('identity', 'random_number');
    }
}
