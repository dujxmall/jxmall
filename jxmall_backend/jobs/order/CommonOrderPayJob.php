<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-30
 * Time: 18:26
 */

namespace app\jobs\order;


use app\jobs\Job;
use app\models\CommonOrder;
use app\models\Order;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class CommonOrderPayJob extends Job
{
    public $common_order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $commonOrder = CommonOrder::findOne(['id' => $this->common_order_id, 'is_pay' => 1]);
        if (!$commonOrder) {
            return false;
        }
        if ($commonOrder->order_class == Order::class) {
            Order::updateAll(['is_pay' => 1, 'pay_time' => $commonOrder->pay_time, 'status' => Order::STATUS_NOT_SEND, 'pay_type' => $commonOrder->pay_type], ['is_pay' => 0, 'id' => $commonOrder->order_id]);
        }
    }
}