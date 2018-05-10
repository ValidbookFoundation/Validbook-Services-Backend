<?php

use yii\db\Migration;

class m170303_113351_create_user_tables extends Migration
{
    public function safeUp()
    {
        // create tables. note the specific order
        $this->createTable('{{%role}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'can_admin' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'email' => $this->string()->defaultValue(null),
            'first_name' => $this->string()->defaultValue(null),
            'last_name' => $this->string()->defaultValue(null),
            'slug' => $this->string()->defaultValue(null),
            'password' => $this->string()->defaultValue(null),
            'public_address' => $this->string()->defaultValue(null),
            'hash' => $this->string()->defaultValue(null),
            'access_token' => $this->string()->defaultValue(null),
            'logged_in_ip' => $this->integer()->defaultValue(null),
            'logged_in_at' => $this->integer()->defaultValue(null),
            'created_ip' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'stories_count' => $this->integer()->defaultValue(null),
        ]);


        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'full_name' => $this->string()->defaultValue(null),
            'timezone' => $this->string()->defaultValue(null),
            'bio' => $this->text()->defaultValue(null),
            'occupation' => $this->string()->defaultValue(null),
            'company' => $this->string()->defaultValue(null),
            'country_id' => $this->string()->defaultValue(null),
            'location' => $this->string()->defaultValue(null),
            'birthDay' => $this->integer()->notNull(),
            'birthMonth' => $this->integer()->notNull(),
            'birthDateVisibility' => $this->smallInteger()->notNull(),
            'birthYear' => $this->integer()->notNull(),
            'birthYearVisibility' => $this->smallInteger()->notNull(),
            'twitter' => $this->string()->defaultValue(null),
            'facebook' => $this->string()->defaultValue(null),
            'linkedin' => $this->string()->defaultValue(null),
            'website' => $this->string()->defaultValue(null),
            'phone' => $this->string()->defaultValue(null),
            'skype' => $this->string()->defaultValue(null),
            'avatar' => $this->string()->defaultValue(null),
            'cover' => $this->string()->defaultValue(null)
        ]);

        $this->createTable('{{%user_auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'provider' => $this->string()->notNull(),
            'provider_id' => $this->integer()->notNull(),
            'provider_attributes' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);

        // add indexes for performance optimization
        $this->createIndex('{{%user_email}}', '{{%user}}', 'email', true);
        $this->createIndex('{{%user_auth_provider_id}}', '{{%user_auth}}', 'provider_id', false);

        // add foreign keys for data integrity
        $this->addForeignKey('{{%user_role_id}}', '{{%user}}', 'role_id', '{{%role}}', 'id');
        $this->addForeignKey('{{%profile_user_id}}', '{{%profile}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%user_auth_user_id}}', '{{%user_auth}}', 'user_id', '{{%user}}', 'id');

        // insert role data
        $columns = ['name', 'can_admin', 'created_at'];
        $this->batchInsert('{{%role}}', $columns, [
            ['Admin', 1, time()],
            ['User', 0, time()],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_auth}}');
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%role}}');
    }

}
