<?php

use yii\db\Schema;
use yii\db\Migration;

class m220403_190424_admin_role extends \app\core\Migration
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
            '{{%admin_role}}',
            [
                'id' => $this->primaryKey(11),
                'admin_id' => $this->integer(11)->comment('admin_id'),
                'role_id' => $this->string(255)->defaultValue('')->comment('角色id'),
            ], $tableOptions
        );
    }

    public function safeDown()
    {

    }
}
