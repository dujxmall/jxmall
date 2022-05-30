<?php

use yii\db\Schema;
use yii\db\Migration;

class m220124_193820_goods_add_is_member_price extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {

        $this->addColumn(\app\models\Goods::tableName(), 'is_member_price', $this->tinyInteger(1)->defaultValue(0)->comment('是否启用会员价'));
        $this->addColumn(\app\models\GoodsInfo::tableName(), 'level_price', $this->string(5120)->defaultValue('')->comment('会员价设置'));
    }

    public function safeDown()
    {

    }
}
