<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_190422_menu_add_plugin extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\Menu::tableName(), 'plugin', $this->string(45)->defaultValue('mall')->comment('插件'));
    }

    public function safeDown()
    {

    }
}
