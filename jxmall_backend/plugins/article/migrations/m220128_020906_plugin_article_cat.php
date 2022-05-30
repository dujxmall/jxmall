<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_020906_plugin_article_cat extends \app\core\Migration
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
            '{{%plugin_article_cat}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'name'=> $this->string(45)->notNull(),
                'cover_pic'=> $this->string(255)->notNull(),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_article_cat}}');
    }
}
