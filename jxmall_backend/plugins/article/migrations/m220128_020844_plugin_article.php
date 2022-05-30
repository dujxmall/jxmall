<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_020844_plugin_article extends \app\core\Migration
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
            '{{%plugin_article}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'title'=> $this->string(45)->notNull(),
                'created_by'=> $this->string(45)->null()->defaultValue('')->comment('由谁创建'),
                'detail'=> $this->text()->notNull()->comment('文章内容'),
                'video'=> $this->string(255)->notNull()->comment('视频')->defaultValue(''),
                'cover_pic'=> $this->string(255)->notNull()->comment('封面图')->defaultValue(''),
                'deleted_at' => $this->integer(11)->null()->defaultValue(0)->comment('删除时间'),
                'updated_at' => $this->integer(11)->null()->defaultValue(0)->comment('更新时间'),
                'created_at' => $this->integer(11)->null()->defaultValue(0)->comment('创建时间'),
                'is_delete'=> $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否删除'),
                'cat_id'=> $this->integer(11)->null()->defaultValue(0)->comment('文章分类'),
                'views'=> $this->integer(11)->notNull()->defaultValue(0)->comment('阅读量'),
                'created_time' => $this->datetime()->notNull()->defaultExpression("CURRENT_TIMESTAMP")->comment('创建时间'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%plugin_article}}');
    }
}
