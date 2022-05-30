<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_021348_plugin_share_level extends \app\core\Migration
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
            '{{%plugin_share_level}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'level'=> $this->integer(11)->notNull(),
                'name'=> $this->string(45)->notNull(),
                'first_price'=> $this->decimal(10, 2)->notNull(),
                'second_price'=> $this->decimal(10, 2)->notNull(),
                'third_price'=> $this->decimal(10, 2)->notNull(),
                'price_type'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0 固定金额  1百分比'),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_share_level}}');
    }
}
