<?php

use yii\db\Schema;
use yii\db\Migration;

class m220330_090422_plugin_integral_sign_log extends \app\core\Migration
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
            '{{%plugin_integral_sign_log}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
                'integral' => $this->integer(11)->notNull()->defaultValue(0)->comment('积分'),
                'date' => $this->string(45)->notNull()->defaultValue('')->comment('日期'),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'deleted_at' => $this->integer(11)->notNull()->defaultValue(0),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'is_delete' => $this->tinyInteger(1)->null()->defaultValue(0),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ], $tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_integral_sign_log}}');
    }
}
