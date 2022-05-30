<?php

use yii\db\Schema;
use yii\db\Migration;

class m220408_190422_menu_add_breadcrumb extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\Menu::tableName(), 'breadcrumb', $this->tinyInteger(1)->defaultValue(0)->comment('是否再面包屑中显示'));
    }

    public function safeDown()
    {

    }
}
