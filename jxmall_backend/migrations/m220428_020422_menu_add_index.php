<?php

use yii\db\Schema;
use yii\db\Migration;

class m220428_020422_menu_add_index extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->createIndex('parent_id', \app\models\Menu::tableName(), ['parent_id'], false);
    }

    public function safeDown()
    {

    }
}
