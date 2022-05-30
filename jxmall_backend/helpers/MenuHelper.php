<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/28
 * Time: 11:42
 * Note:
 */

namespace app\helpers;


class MenuHelper
{

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/28
     * Time：14:26
     * Note：创建菜单迁移文件的模板
     */
    public static function createMenu()
    {
        return [
            'menu_name' => '',
            'icon' => 'nested',
            'parent_id' => '',
            'menu_id' => '',
            'menu_type' => 'C',
            'order_num' => '',
            'is_cache' => 0,
            'hidden' => 0,
            'is_frame' => 0,
            'status' => '0',
            'path' => '',
            'component' => '',
            'query' => '',
            'perms' => '',
            'link' => '',
            'breadcrumb' => 1,
            'show_tag' => 1,
            'active_menu' => '',
            'name' => '',
            'plugin' => 'mall'
        ];
    }
}