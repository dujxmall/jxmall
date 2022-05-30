<?php

use yii\db\Schema;
use yii\db\Migration;

class m220124_193820_goods_add_limit_num extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {


        $this->addColumn(\app\models\Goods::tableName(),'limit_num',$this->integer(11)->defaultValue(0)->comment('限购数量'));

    }

    public function safeDown()
    {

    }
}
