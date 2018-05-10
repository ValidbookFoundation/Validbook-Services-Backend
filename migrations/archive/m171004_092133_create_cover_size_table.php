<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cover_size`.
 */
class m171004_092133_create_cover_size_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cover_size', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'size' => $this->string(25)->notNull(),
            'url' => $this->string(255)->notNull(),
            'is_actual' => $this->boolean()->defaultValue(1)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cover_size');
    }
}
