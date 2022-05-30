<?php

use yii\db\Schema;
use yii\db\Migration;

class m220324_100422_user_add_openid extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\User::tableName(), 'openid', $this->string(64)->defaultValue('')
            ->comment('临时openid，用户登录就会重新覆盖新的openid'));
    }


}
