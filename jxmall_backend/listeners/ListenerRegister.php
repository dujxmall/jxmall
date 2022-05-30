<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-22
 * Time: 14:28
 */

namespace app\listeners;


class ListenerRegister
{
    public function getListeners()
    {
        return [
            CommonOrderListener::class,
            UnionOrderPayListener::class,
            CommonOrderPayListener::class,
            OrderStatusListener::class,
            OrderCreatedListener::class,
            UserListener::class,
            CheckLevelUpgradeListener::class,
            UnoionOrderOnWechatOrderPayListener::class,
            CommonOrderOnWechatOrderPayListener::class
        ];
    }
}
