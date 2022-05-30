<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-15
 * Time: 10:36
 */

namespace app\jobs\order;


use app\helpers\IntegralLogHelper;
use app\jobs\Job;
use app\models\CommonOrder;
use app\models\Coupon;
use app\models\Order;
use app\models\UserCoupon;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class OrderAutoCancelJob extends Job
{

    public $id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $order = Order::findOne(['id' => $this->id, 'is_delete' => 0, 'status' => Order::STATUS_NOT_PAY]);
        if (!$order) {
            return;
        }
        \Yii::warning('订单自动取消');
        $order->status = Order::STATUS_CANCEL;
        $order->save();
    }
}