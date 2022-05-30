<?php

use yii\db\Schema;
use yii\db\Migration;

class m220329_190422_hot_image extends \app\core\Migration
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
            '{{%hot_image}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->notNull(),
                'name' => $this->string(45)->defaultValue('')->comment('热图名称'),
                'pic_url' => $this->string(255)->defaultValue('')->comment('图片链接'),
                'data' => $this->string(10240)->notNull()->defaultValue('')->comment('数据'),

            ], $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%hot_image}}');
    }
}
