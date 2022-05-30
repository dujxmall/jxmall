<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_190422_admin_add_nickname extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Admin::tableName(),'nickname',$this->string(45)->defaultValue('')->comment('昵称'));
    }

    public function safeDown()
    {

    }
}
