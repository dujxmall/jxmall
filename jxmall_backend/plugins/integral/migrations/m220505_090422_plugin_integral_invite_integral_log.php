<?php

use yii\db\Schema;
use yii\db\Migration;

class m220505_090422_plugin_integral_invite_integral_log extends \app\core\Migration
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
            '{{%plugin_integral_invite_integral_log}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
                'parent_user_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('推荐人用户ID'),
                'integral' => $this->integer(11)->notNull()->defaultValue(0)->comment('积分'),
            ], $tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_integral_invite_integral_log}}');
    }
}
