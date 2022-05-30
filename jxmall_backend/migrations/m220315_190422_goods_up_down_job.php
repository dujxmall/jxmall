<?php

use yii\db\Schema;
use yii\db\Migration;

class m220315_190422_goods_up_down_job extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB CHARSET=utf8mb4';
        $this->createTable(
            '{{%goods_up_down_job}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->notNull(),
                'goods_id' => $this->integer(11)->defaultValue(0)->comment('商品ID'),
                'action_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('执行时间'),
                'action_time' => $this->string(45)->notNull()->defaultValue('')->comment('执行时间'),
                'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('1下架  2 上架'),
                'is_done' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否执行'),
            ], $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%goods_up_down_job}}');
    }
}
