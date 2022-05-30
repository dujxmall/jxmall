<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-14
 * Time: 17:58
 */

namespace app\listeners;


use app\events\OrderEvent;
use app\jobs\order\OrderStatusChangeJob;

class OrderStatusListener extends BaseListener
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
        \Yii::$app->on(OrderEvent::STATUS_CHANGE, function ($event) {
            \app\helpers\JobHelper::push(new OrderStatusChangeJob(['order_id' => $event->order_id]));
        });
    }
}