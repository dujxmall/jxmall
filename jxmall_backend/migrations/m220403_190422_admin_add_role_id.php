<?php

use yii\db\Schema;
use yii\db\Migration;

class m220403_190422_admin_add_role_id extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Admin::tableName(), 'role_id', $this->integer(11)->defaultValue(0)->comment('角色ID'));
    }

    public function safeDown()
    {

    }
}
