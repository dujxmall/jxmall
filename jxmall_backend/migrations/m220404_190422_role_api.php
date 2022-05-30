<?php

use yii\db\Schema;
use yii\db\Migration;

class m220404_190422_role_api extends \app\core\Migration
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
            '{{%role_api}}',
            [
                'id' => $this->primaryKey(11),
                'path' => $this->string(255)->defaultValue('')->comment('接口路径'),
                'role_id' => $this->string(90)->defaultValue('')->comment('角色ID'),
                'method' => $this->string(10)->defaultValue('')->comment('请求方法'),
                'uniqueid' => $this->string(255)->defaultValue('')->comment('唯一ID'),

            ], $tableOptions
        );
    }

    public function safeDown()
    {

    }
}
