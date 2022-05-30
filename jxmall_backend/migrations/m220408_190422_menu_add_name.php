<?php

use yii\db\Schema;
use yii\db\Migration;

class m220408_190422_menu_add_name extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\Menu::tableName(), 'name', $this->string(64)->defaultValue('')->comment('组件名称'));
    }

    public function safeDown()
    {

    }
}
