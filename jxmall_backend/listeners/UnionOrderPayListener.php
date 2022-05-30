<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-22
 * Time: 14:16
 */

namespace app\listeners;


use app\events\UnionOrderEvent;
use app\jobs\order\UnionOrderPayJob;

class UnionOrderPayListener extends BaseListener
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
        \Yii::$app->on(UnionOrderEvent::UNION_ORDER_PAY, function ($event) {
            $union_order_id = $event->union_order_id;
            \app\helpers\JobHelper::push(new UnionOrderPayJob(['union_order_id' => $union_order_id]));
        });
    }
}