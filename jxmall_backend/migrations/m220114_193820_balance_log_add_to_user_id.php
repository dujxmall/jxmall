<?php

use yii\db\Schema;
use yii\db\Migration;

class m220114_193820_balance_log_add_to_user_id extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\BalanceLog::tableName(), 'to_user_id', $this->integer(11)->defaultValue(0)->comment('给哪个用户'));
    }

    public function safeDown()
    {

    }
}
