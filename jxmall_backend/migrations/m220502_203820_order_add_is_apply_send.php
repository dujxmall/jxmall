<?php

use yii\db\Schema;
use yii\db\Migration;

class m220502_203820_order_add_is_apply_send extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $this->addColumn(\app\models\Order::tableName(), 'is_apply_send', $this->integer(1)->defaultValue(0)->comment('申请发货'));
    }

    public function safeDown()
    {

    }
}
