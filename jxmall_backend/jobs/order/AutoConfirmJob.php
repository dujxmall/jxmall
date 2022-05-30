<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-15
 * Time: 15:26
 */

namespace app\jobs\order;


use app\jobs\Job;
use app\models\Order;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class AutoConfirmJob extends Job
{

    public $order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $order = Order::findOne(['is_delete' => 0, 'status' => Order::STATUS_NOT_CONFIRM, 'id' => $this->order_id]);
        if (!$order) {
            return;
        }
        \Yii::warning('自动收货队列');
        $order->status = Order::STATUS_IS_CONFIRM;
        $order->is_confirm = 1;
        $order->confirm_at = time();
        $order->save();
    }
}