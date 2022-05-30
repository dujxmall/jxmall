<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:57
 */

namespace app\plugins;


abstract class Plugin
{
    const TYPE_PRICE = 0;
    const TYPE_MARKET = 1;
    const TYPE_TOOL = 2;

    abstract public function getLabel();

    abstract public function getName();

    abstract public function getLogo();

    abstract public function getType();

    abstract public function getVersion();

    abstract public function getHistoryVersion();

    abstract public function getDsc();

    /**
     * 获取插件菜单列表
     * @return array
     */
    abstract public function getMenus();

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-08-08
     * @Time: 11:38
     * @Note:获取插件后台管理菜单
     * @return mixed
     */
    abstract public function getAdminMenus();

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 16:59
     * @Note:获取事件监听器
     */
    abstract public function getListeners();


}