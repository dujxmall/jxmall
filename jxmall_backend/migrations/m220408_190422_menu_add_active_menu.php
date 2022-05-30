<?php

use yii\db\Schema;
use yii\db\Migration;

class m220408_190422_menu_add_active_menu extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Menu::tableName(), 'active_menu', $this->string(64)->defaultValue('')->comment('高亮菜单'));
    }

    public function safeDown()
    {

    }
}
