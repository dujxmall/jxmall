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


use app\events\CommonOrderDetailEvent;
use app\events\CommonOrderEvent;
use app\listeners\BaseListener;
use app\plugins\share\jobs\ShareOrderCreatedJob;
use app\plugins\share\jobs\ShareOrderStatusJob;

class CommonOrderDetailStatusListener extends BaseListener
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
        \Yii::$app->on(CommonOrderDetailEvent::VALID, function ($event) {
            \app\helpers\JobHelper::push(new ShareOrderStatusJob(['common_order_id' => $event->common_order_id, 'common_order_detail_id' => $event->id, 'status' => 1]));
        });
        \Yii::$app->on(CommonOrderDetailEvent::INVALID, function ($event) {
            \app\helpers\JobHelper::push(new ShareOrderStatusJob(['common_order_id' => $event->common_order_id, 'common_order_detail_id' => $event->id, 'status' => 2]));
        });
    }
}