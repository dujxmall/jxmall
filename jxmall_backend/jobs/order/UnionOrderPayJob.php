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


use app\models\CommonOrder;
use app\models\UnionOrder;
use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\JobInterface;
use yii\queue\Queue;

class UnionOrderPayJob extends \app\jobs\Job
{

    public $union_order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        \Yii::warning('合并订单事件,进入队列中');
        // TODO: Implement execute() method.
        $unionOrder = UnionOrder::findOne(['id' => $this->union_order_id, 'is_pay' => 1]);
        if (!$unionOrder) {
            return false;
        }
        $order_list = CommonOrder::find()->where(['union_no' => $unionOrder->union_no, 'is_pay' => 0])->all();


        foreach ($order_list as $order) {
            /**
             * @var CommonOrder $order ;
             */
            $order->is_pay = 1;
            $order->pay_time = time();
            $order->pay_type = $unionOrder->pay_type;
            $order->save();
        }
    }
}