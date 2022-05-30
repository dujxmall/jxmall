<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-15
 * Time: 10:25
 */

namespace app\listeners;


use app\events\OrderEvent;
use app\helpers\JobHelper;
use app\helpers\OptionHelper;
use app\jobs\order\OrderAutoCancelJob;
use app\models\Order;

class OrderCreatedListener extends BaseListener
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

        \Yii::$app->on(OrderEvent::ORDER_CREATED, function ($event) {
            $order = Order::findOne($event->order_id);
            if ($order) {
                $mall_setting = OptionHelper::get('mall_setting', $order->mall_id);
                if ($mall_setting) {
                    if (!$mall_setting['over_time']) {
                        $mall_setting['over_time'] = 5;
                    }
                }else{
                    $mall_setting['over_time'] = 5;
                }
              JobHelper::push(new OrderAutoCancelJob(['id' => $order->id]),$mall_setting['over_time'] * 60);

            }
        });
    }
}
