<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_190422_admin_add_email extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Admin::tableName(), 'email', $this->string(64)->defaultValue('')->comment('email'));
    }

    public function safeDown()
    {

    }
}
