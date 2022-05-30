<?php

use yii\db\Schema;
use yii\db\Migration;

class m220502_203820_level_add_is_inviter extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $this->addColumn(\app\models\Level::tableName(), 'is_inviter', $this->integer(1)->defaultValue(0)->comment('是否具有推广权限'));

    }

    public function safeDown()
    {

    }
}
