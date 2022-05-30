<?php

use yii\db\Schema;
use yii\db\Migration;

class m220511_190422_market_menu_add_coupon_send extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $menu = \app\helpers\MenuHelper::createMenu();
        $menu['menu_name'] = '手动发券';
        $menu['menu_id'] = '60003';
        $menu['order_num'] = 3;
        $menu['component'] = 'market/coupon-send';
        $menu['name'] = 'market-coupon-send';
        $menu['parent_id'] = 6;
        $menu['is_cache'] = 0;
        $menu['path'] = 'coupon-send';
        $menu['show_tag'] = 1;
        $menu['hidden'] = 1;
        $menu['plugin'] = 'mall';
        $menu['active_menu'] = '/market/coupon';
        $this->insert(\app\models\Menu::tableName(), $menu);

    }

    public function safeDown()
    {

    }
}
