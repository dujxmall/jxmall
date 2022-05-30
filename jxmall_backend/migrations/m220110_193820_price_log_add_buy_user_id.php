<?php

use yii\db\Schema;
use yii\db\Migration;

class m220110_193820_price_log_add_buy_user_id extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $this->addColumn(\app\models\PriceLog::tableName(), 'buy_user_id', $this->integer(11)->comment('购买者的ID')->notNull()->defaultValue(0));
        $this->addColumn(\app\models\PriceLog::tableName(), 'order_no', $this->string(45)->comment('订单号')->notNull()->defaultValue(''));


    }

    public function safeDown()
    {

    }
}
