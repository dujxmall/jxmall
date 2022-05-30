<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_021319_plugin_share extends \app\core\Migration
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
            '{{%plugin_share}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'user_id'=> $this->integer(11)->notNull(),
                'level'=> $this->integer(11)->notNull()->defaultValue(0),
                'total_price'=> $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('累计分销佣金'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'is_auto'=> $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('是否自动升级'),
                'level_id'=> $this->integer(11)->null()->defaultValue(0),
                'level_at'=> $this->integer(11)->null()->defaultValue(0),
                'is_manual'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否手动'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_share}}');
    }
}
