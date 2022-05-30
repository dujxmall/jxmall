<?php

use yii\db\Schema;
use yii\db\Migration;

class m220505_203820_role_add_created_by extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $this->addColumn(\app\models\Role::tableName(), 'created_by', $this->integer(11)->defaultValue(0)->comment('创建人'));

    }

    public function safeDown()
    {

    }
}
