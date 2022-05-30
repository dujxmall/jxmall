<?php

use yii\db\Schema;
use yii\db\Migration;

class m220404_190422_role_table extends \app\core\Migration
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
            '{{%role}}',
            [
                'id' => $this->primaryKey(11),
                'role_name' => $this->string(45)->defaultValue('')->comment('role_name'),
                'role_key' => $this->string(255)->defaultValue('')->comment('role_key'),
                'role_sort' => $this->integer(11)->defaultValue(0)->comment('排序'),
                'remark' => $this->string(255)->defaultValue('')->comment('备注'),
                'status' => $this->tinyInteger(1)->defaultValue(0)->comment('状态'),
                'menu_check_strictly' => $this->tinyInteger(1)->defaultValue(0)->comment('menu_check_strictly'),
                'admin' => $this->boolean()->defaultValue(false)->comment('是否是管理员'),
            ], $tableOptions
        );
    }

    public function safeDown()
    {

    }
}
