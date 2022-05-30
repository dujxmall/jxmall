<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-15
 * Time: 15:34
 */

namespace app\jobs\order;


use app\jobs\Job;
use app\models\Order;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class OrderCompleteJob extends Job
{
    public $order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $order = Order::findOne(['is_delete' => 0, 'status' => Order::STATUS_IS_CONFIRM, 'id' => $this->order_id]);
        if (!$order) {
            return;
        }
        \Yii::warning('自动完成队列');
        $order->status = Order::STATUS_IS_COMPLETE;
        $order->is_finish = 1;
        $order->finish_at = time();
        $order->save();
    }
}