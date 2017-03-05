<?php

/**
 * Created by PhpStorm.
 * User: yav
 * Date: 05.03.17
 * Time: 15:18
 */
use yii\db\Migration;

class m170305_150000_profile extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'filefoto' => $this->string(256)->null(),

        ], $tableOptions);
        $this->addForeignKey('FK_profile_user','{{%profile}}','user_id','{{%user}}','id','NO ACTION');


        $this->createTable('{{%profile_fields}}', [
            'id' => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'field_name' => $this->string(32)->null()->comment('Наименование'),
            'field_value' => $this->string(32)->null()->comment('Значение'),

        ], $tableOptions);

        $this->addForeignKey('FK_profile_fields_profile','{{%profile_fields}}','profile_id','{{%profile}}','id','NO ACTION');
    }

    public function safeDown()
    {

        $this->dropTable('{{%profile_fields}}');
        $this->dropTable('{{%profile}}');
    }
}