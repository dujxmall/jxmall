<?php

use yii\db\Schema;
use yii\db\Migration;

class m220324_100422_user_add_password extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\User::tableName(), 'password', $this->string(64)->defaultValue('')
            ->comment('密码'));
    }


}
