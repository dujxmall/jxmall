<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_021354_plugin_share_order extends \app\core\Migration
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
            '{{%plugin_share_order}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'user_id'=> $this->integer(11)->notNull(),
                'common_order_id'=> $this->integer(11)->notNull()->comment('公共订单ID'),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'parent_id_1'=> $this->integer(11)->notNull()->defaultValue(0),
                'parent_id_2'=> $this->integer(11)->notNull()->defaultValue(0),
                'parent_id_3'=> $this->integer(11)->notNull()->defaultValue(0),
                'share_price'=> $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('分销佣金、'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_share_order}}');
    }
}
