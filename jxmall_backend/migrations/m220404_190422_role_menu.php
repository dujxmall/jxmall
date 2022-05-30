<?php

use yii\db\Schema;
use yii\db\Migration;

class m220404_190422_role_menu extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB CHARSET=utf8mb4';
        $this->createTable(
            '{{%role_menu}}',
            [
                'id' => $this->primaryKey(11),
                'role_id' => $this->integer(11)->defaultValue(0)->comment('role_id'),
                'menu_id' => $this->string(45)->defaultValue('')->comment('menu_id'),

            ], $tableOptions
        );
    }

    public function safeDown()
    {

    }
}
