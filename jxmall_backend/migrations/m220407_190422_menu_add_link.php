<?php

use yii\db\Schema;
use yii\db\Migration;

class m220407_190422_menu_add_link extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

         $this->addColumn(\app\models\Menu::tableName(),'link',$this->string(255)->defaultValue('')->comment('链接'));
    }

    public function safeDown()
    {

    }
}
