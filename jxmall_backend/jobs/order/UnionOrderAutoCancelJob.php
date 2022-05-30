<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-26
 * Time: 8:58
 */

namespace app\jobs\order;


use app\jobs\Job;
use app\models\UnionOrder;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class UnionOrderAutoCancelJob extends Job
{

    public $order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $order = UnionOrder::findOne(['is_pay' => 0, 'id' => $this->order_id, 'is_delete' => 0]);
        if (!$order) {
            return;
        }
        $order->is_cancel = 1;
        $order->save();
    }
}