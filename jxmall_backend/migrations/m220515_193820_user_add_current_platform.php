<?php

use yii\db\Schema;
use yii\db\Migration;

class m220515_193820_user_add_current_platform extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\User::tableName(), 'current_platform', $this->string(15)->defaultValue('')->comment('用户当前所在平台'));


    }

    public function safeDown()
    {

    }
}
