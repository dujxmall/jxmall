<?php

use yii\db\Schema;
use yii\db\Migration;

class m220527_190422_menu_add_outside_link extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $menu = \app\helpers\MenuHelper::createMenu();
        $menu['menu_name'] = '外部链接';
        $menu['menu_id'] = '100018';
        $menu['order_num'] = 6;
        $menu['component'] = 'mall/outside-link';
        $menu['name'] = 'mall-outside-link';
        $menu['parent_id'] = 1;
        $menu['is_cache'] = 0;
        $menu['path'] = 'outside-link';
        $menu['show_tag'] = 1;
        $menu['hidden'] = 0;
        $menu['plugin'] = 'mall';
        $menu['active_menu'] = '/mall/outside-link';
        $this->insert(\app\models\Menu::tableName(), $menu);

    }

    public function safeDown()
    {

    }
}
