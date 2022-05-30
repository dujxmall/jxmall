<?php

use yii\db\Schema;
use yii\db\Migration;

class m220502_203820_order_add_apply_send_time extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $this->addColumn(\app\models\Order::tableName(), 'apply_send_time', $this->timestamp()->null()->comment('申请时间'));
    }

    public function safeDown()
    {

    }
}
