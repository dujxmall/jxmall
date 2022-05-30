<?php

use yii\db\Schema;
use yii\db\Migration;

class m220404_190422_api extends \app\core\Migration
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
            '{{%api}}',
            [
                'id' => $this->primaryKey(11),
                'path' => $this->string(255)->defaultValue('')->comment('接口路径'),
                'api_group' => $this->string(255)->defaultValue('')->comment('分组'),
                'method' => $this->string(10)->defaultValue('')->comment('请求方法'),
                'description' => $this->string(255)->defaultValue('')->comment('简介'),
                'uniqueid' => $this->string(255)->defaultValue('')->comment('唯一ID'),

            ], $tableOptions
        );
    }

    public function safeDown()
    {

    }
}
