<?php

use yii\db\Schema;
use yii\db\Migration;

class m220122_193820_level_add_buy_type extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {


        $this->addColumn(\app\models\Level::tableName(),'buy_type',$this->tinyInteger(1)->defaultValue(0)->comment('0支付 1 订单完成'));

    }

    public function safeDown()
    {

    }
}
