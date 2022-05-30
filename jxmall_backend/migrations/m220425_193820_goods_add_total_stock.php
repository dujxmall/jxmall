<?php

use yii\db\Schema;
use yii\db\Migration;

class m220425_193820_goods_add_total_stock extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Goods::tableName(), 'total_stock', $this->integer(11)->defaultValue(0)->comment('累计库存'));


    }

    public function safeDown()
    {

    }
}
