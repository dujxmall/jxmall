<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-31
 * Time: 16:56
 */

namespace app\plugins\share\listeners;


use app\events\CommonOrderEvent;
use app\helpers\JobHelper;
use app\listeners\BaseListener;
use app\plugins\share\jobs\ShareOrderCreatedJob;

class CommonOrderCreatedListener extends BaseListener
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-22
     * @Time: 14:15
     * @Note:所有继承该类的都要实现事件注册的方法
     * @return mixed
     */
    function register()
    {
        // TODO: Implement register() method.
        \Yii::$app->on(CommonOrderEvent::COMMON_ORDER_CREATED, function ($event) {
            JobHelper::push(new ShareOrderCreatedJob(['common_order_id' => $event->id]));
        });
    }
}