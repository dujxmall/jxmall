<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_021342_plugin_share_goods extends \app\core\Migration
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
            '{{%plugin_share_goods}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'goods_id'=> $this->integer(11)->notNull()->comment('商品ID'),
                'goods_type'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'is_share_goods'=> $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('是否是分销商品'),
                'is_alone'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('独立设置分销'),
                'price_type'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 固定金额 1 百分比'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_share_goods}}');
    }
}
