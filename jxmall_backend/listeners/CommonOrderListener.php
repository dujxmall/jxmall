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


use app\events\CommonOrderEvent;
use app\helpers\JobHelper;
use app\helpers\OptionHelper;
use app\jobs\order\CommonOrderDetailStatusJob;
use app\jobs\order\CommonOrderStatusChangeJob;
use app\jobs\order\OrderAutoCancelJob;
use app\models\CommonOrder;

class CommonOrderListener extends BaseListener
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
        //有效订单监听
        \Yii::$app->on(CommonOrderEvent::COMMON_ORDER_STATUS_CHANGE, function ($event) {
            JobHelper::push(new CommonOrderStatusChangeJob(['id' => $event->id]), 3);
        });
    }
}
