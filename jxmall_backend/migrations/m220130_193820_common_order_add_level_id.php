<?php

use yii\db\Schema;
use yii\db\Migration;

class m220130_193820_common_order_add_level_id extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\CommonOrder::tableName(), 'level_id', $this->integer(11)->defaultValue(0)->comment('下单时候用户等级ID'));
        $this->addColumn(\app\models\CommonOrder::tableName(), 'level', $this->integer(11)->defaultValue(0)->comment('下单时候用户等级'));

    }

    public function safeDown()
    {

    }
}
