<?php

use yii\db\Migration;

class m170816_113826_create_table_knock_book extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('knock_book', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'book_author_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_in_knock_book', 'knock_book', 'book_id, book_author_id, user_id', true);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('knock_book');
    }

}
