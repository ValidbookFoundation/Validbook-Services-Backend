<?php

use yii\db\Migration;

/**
 * Handles the creation of table `h_card_digital_property`.
 */
class m170914_071651_create_h_card_digital_property_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('h_card_digital_property', [
            'id' => $this->primaryKey(),
            'property' => $this->smallInteger()->notNull(),
            'human_card_id' => $this->integer()->notNull(),
            'url_property' => $this->string()->notNull(),
            'random_number' => $this->bigInteger(12)->defaultValue(null),
            'url_proof' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'is_valid' => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('h_card_digital_property');
    }
}
