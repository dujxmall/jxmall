<?php

use yii\db\Schema;
use yii\db\Migration;

class m220310_100422_user_add_birthday extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\User::tableName(), 'birthday', $this->string(45)->defaultValue('')
            ->comment('会员生日'));
    }


}
