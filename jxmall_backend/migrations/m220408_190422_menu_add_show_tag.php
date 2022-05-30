<?php

use yii\db\Schema;
use yii\db\Migration;

class m220408_190422_menu_add_show_tag extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\Menu::tableName(), 'show_tag', $this->tinyInteger(1)->defaultValue(0)->comment('是否显示标签'));
    }

    public function safeDown()
    {

    }
}
