<?php

use yii\db\Schema;
use yii\db\Migration;

class m220404_190422_menu_table extends \app\core\Migration
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
            '{{%menu}}',
            [
                'id' => $this->primaryKey(11),
                'menu_id' => $this->string(45)->unique()->defaultValue('')->comment('menu_id'),
                'menu_name' => $this->string(45)->defaultValue('')->comment('role_name'),
                'icon' => $this->string(45)->defaultValue('')->comment('role_name'),
                'menu_type' => $this->string(45)->defaultValue('')->comment('role_key'),
                'order_num' => $this->integer(11)->defaultValue(0)->comment('order_num'),
                'parent_id' => $this->string(45)->defaultValue('')->comment('parent_id'),
                'is_cache' => $this->tinyInteger(1)->defaultValue(0)->comment('is_cache'),
                'hidden' => $this->tinyInteger(1)->defaultValue(0)->comment('hidden'),
                'is_frame' => $this->tinyInteger(1)->defaultValue(0)->comment('is_frame'),
                'status' => $this->tinyInteger(1)->defaultValue(0)->comment('path'),
                'path' => $this->string(255)->defaultValue('')->comment('状态'),
                'component' => $this->string(255)->defaultValue('')->comment('component'),
                'query' => $this->string(255)->defaultValue('')->comment('query'),
                'perms' => $this->string(255)->defaultValue('')->comment('perms'),

            ], $tableOptions
        );
    }

    public function safeDown()
    {

    }
}
