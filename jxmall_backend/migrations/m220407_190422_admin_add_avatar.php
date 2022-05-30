<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_190422_admin_add_avatar extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Admin::tableName(),'avatar',$this->string(255)->defaultValue('')->comment('头像'));
    }

    public function safeDown()
    {

    }
}
