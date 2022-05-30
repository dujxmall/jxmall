<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_131427_admin_roleDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%admin_role}}',
            ["id", "admin_id", "role_id", "updated_at", "deleted_at", "created_at", "is_delete"],
            [
                [
                    'id' => '1',
                    'admin_id' => '1',
                    'role_id' => '1',
                    'updated_at' => '1649324293',
                    'deleted_at' => '0',
                    'created_at' => '1649324293',
                    'is_delete' => '0',
                ],
            ]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%admin_role}} CASCADE');
    }
}
