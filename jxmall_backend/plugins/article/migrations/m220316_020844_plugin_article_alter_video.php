<?php

use yii\db\Schema;
use yii\db\Migration;

class m220316_020844_plugin_article_alter_video extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->alterColumn(\app\plugins\article\models\Article::tableName(), 'video', $this->string(255)->defaultValue('')->comment('视频链接'));
        $this->alterColumn(\app\plugins\article\models\Article::tableName(), 'cover_pic', $this->string(255)->defaultValue('')->comment('文章封面图'));

    }

    public function safeDown()
    {

    }
}
