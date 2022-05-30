<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_131305_roleDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%role}}',
            ["id", "role_name", "role_key", "role_sort", "remark", "status", "menu_check_strictly", "updated_at", "deleted_at", "created_at", "is_delete", "created_time", "admin"],
            [
                [
                    'id' => '1',
                    'role_name' => '超级管理员',
                    'role_key' => 'admin',
                    'role_sort' => '0',
                    'remark' => '',
                    'status' => '0',
                    'menu_check_strictly' => '1',
                    'updated_at' => '1649318915',
                    'deleted_at' => '0',
                    'created_at' => '1649232639',
                    'is_delete' => '0',
                    'created_time' => '2022-04-06 16:10:39',
                    'admin' => '1',
                ],
                [
                    'id' => '2',
                    'role_name' => '普通用户',
                    'role_key' => 'common',
                    'role_sort' => '2',
                    'remark' => '普通角色',
                    'status' => '0',
                    'menu_check_strictly' => '1',
                    'updated_at' => '1649321891',
                    'deleted_at' => '0',
                    'created_at' => '1649296508',
                    'is_delete' => '0',
                    'created_time' => '2022-04-07 09:55:08',
                    'admin' => '0',
                ],
            ]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%role}} CASCADE');
    }
}
