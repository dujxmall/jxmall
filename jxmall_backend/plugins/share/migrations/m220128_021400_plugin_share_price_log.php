<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_021400_plugin_share_price_log extends \app\core\Migration
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
            '{{%plugin_share_price_log}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'user_id'=> $this->integer(11)->notNull(),
                'common_order_detail_id'=> $this->integer(11)->notNull()->comment('公共订单详情ID'),
                'price'=> $this->decimal(10, 2)->notNull()->defaultValue('0.00'),
                'status'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 未发放  1发放 2无效'),
                'share_order_id'=> $this->integer(11)->notNull(),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'common_order_id'=> $this->integer(11)->notNull()->comment('公共订单ID'),
                'goods_type'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
                'goods_id'=> $this->integer(11)->notNull()->comment('商品ID'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_share_price_log}}');
    }
}
