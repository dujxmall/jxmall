<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 9:02
 */

namespace app\jobs\order;


use app\core\PaymentType;
use app\events\CommonOrderDetailEvent;
use app\events\CommonOrderEvent;
use app\helpers\BalanceLogHelper;
use app\helpers\JobHelper;
use app\jobs\goods\GoodsSalesJob;
use app\jobs\Job;
use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\models\OrderRefund;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class CommonOrderDetailStatusJob extends Job
{

    public $common_order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $common_order = CommonOrder::findOne(['is_delete' => 0, 'id' => $this->common_order_id, 'status' => 1]);
        if (!$common_order) {
            return;
        }
        if ($common_order->order_type == CommonOrder::TYPE_MALL) {
            $list = CommonOrderDetail::find()->where(['common_order_id' => $this->common_order_id, 'is_delete' => 0])->all();
            foreach ($list as $item) {
                /**
                 * @var CommonOrderDetail $item
                 */
                $event = new CommonOrderDetailEvent();
                $event->id = $item->id;
                $event->common_order_id = $this->common_order_id;
                $refund = OrderRefund::findOne(['is_delete' => 0, 'order_detail_id' => $item->order_detail_id]);
                if ($refund) {
                    //存在售后 看看售后类型是不是
                    //售后类型是退款退货，但是后台没有处理，强制退款退货
                    if ($refund->type == 0) {
                        if ($refund->status == 0) {
                            $item->status = 2;
                            $item->save();
                            $refund->status = 1;
                            if ($refund->save()) {
                                if ($common_order->pay_type == PaymentType::TYPE_BALANCE) {
                                    BalanceLogHelper::add($common_order->user_id, $refund->refund_price, "售后订单: {$refund->order_no},退款");
                                }
                            }
                        }
                        if ($refund->status == 1) {//同意了，就是已经退钱了
                            $item->status = 2;
                            $item->save();
                        }
                        if ($refund->status == 2) {//拒绝了
                            $item->status = 1;
                            $item->save();
                        }
                    }
                    if ($refund->type == 1) {
                        $item->status = 1;
                        $item->save();
                    }
                }
                if (!$refund) {
                    $item->status = 1;
                    $item->save();
                }
                if ($item->status == 1) {
                    \Yii::$app->trigger(CommonOrderDetailEvent::VALID, $event);
                } else {
                    \Yii::$app->trigger(CommonOrderDetailEvent::INVALID, $event);
                    JobHelper::push(new GoodsSalesJob(['common_order_detail_id' => $event->id, 0]), 3);
                }
            }
        }
    }
}