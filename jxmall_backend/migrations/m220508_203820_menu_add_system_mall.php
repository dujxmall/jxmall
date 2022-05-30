<?php

use yii\db\Schema;
use yii\db\Migration;

class m220508_203820_menu_add_system_mall extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $menu = \app\helpers\MenuHelper::createMenu();
        $menu['menu_name'] = '商城管理';
        $menu['menu_id']='100006';
        $menu['path'] = 'mall/list';
        $menu['order_num'] = 6;
        $menu['component'] = 'system/mall/list';
        $menu['name'] = 'system-mall-list';
        $menu['parent_id'] = 10;
        $menu['is_cache'] = 0;
        $this->insert(\app\models\Menu::tableName(), $menu);
    }

    public function safeDown()
    {

    }
}
