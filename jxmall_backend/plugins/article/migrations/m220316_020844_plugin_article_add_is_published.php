<?php

use yii\db\Schema;
use yii\db\Migration;

class m220316_020844_plugin_article_add_is_published extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\plugins\article\models\Article::tableName(), 'is_published', $this->tinyInteger(1)->defaultValue(0)->comment('0 未发布 1已发布'));
        $this->addColumn(\app\plugins\article\models\Article::tableName(), 'sort', $this->integer(11)->defaultValue(0)->comment('排序'));
    }

    public function safeDown()
    {

    }
}
