<?php

use yii\db\Schema;
use yii\db\Migration;

class m220129_193820_cash_add_order_no extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Cash::tableName(), 'order_no', $this->string(45)->comment('提现单号')->defaultValue(''));
    }

    public function safeDown()
    {

    }
}
