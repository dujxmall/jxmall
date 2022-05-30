<?php

use yii\db\Schema;
use yii\db\Migration;

class m220314_190422_feedback extends \app\core\Migration
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
            '{{%feedback}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->defaultValue(0)->comment('用户ID'),
                'content' => $this->string(2048)->notNull()->defaultValue('')->comment('反馈内容'),
            ], $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%feedback}}');
    }
}
