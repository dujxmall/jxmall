<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_131129_mallDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%mall}}',
            ["id", "admin_id", "name", "is_has_expire", "end_at", "is_expire", "is_stop", "logo", "detail", "uniacid", "updated_at", "created_at", "deleted_at", "created_time", "is_delete"],
            [
                [
                    'id' => '1',
                    'admin_id' => '1',
                    'name' => '聚象商城',
                    'is_has_expire' => '0',
                    'end_at' => null,
                    'is_expire' => '0',
                    'is_stop' => '0',
                    'logo' => '',
                    'detail' => null,
                    'uniacid' => '0',
                    'updated_at' => '1649126002',
                    'created_at' => '1648221995',
                    'deleted_at' => '0',
                    'created_time' => '2022-03-25 23:26:35',
                    'is_delete' => '0',
                ],
            ]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%mall}} CASCADE');
    }
}
