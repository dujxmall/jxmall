<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-14
 * Time: 17:59
 */

namespace app\jobs\order;


use app\core\PaymentType;
use app\helpers\BalanceLogHelper;
use app\helpers\ConstantHelper;
use app\helpers\IntegralLogHelper;
use app\helpers\JobHelper;
use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\helpers\WechatHelper;
use app\jobs\Job;
use app\models\CommonOrder;
use app\models\Coupon;
use app\models\Order;
use app\models\UnionOrder;
use app\models\UserCoupon;
use app\models\UserInfo;
use function Codeception\Extension\codecept_log;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\queue\JobInterface;
use yii\queue\Queue;

class OrderStatusChangeJob extends Job
{
    public $order_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        \Yii::warning('订单状态改变队列');
        $order = Order::findOne($this->order_id);
        if (!$order) {
            return;
        }
        //订单取消
        if ($order->status == Order::STATUS_CANCEL) {
            $commonOrder = CommonOrder::findOne(['order_id' => $this->order_id, 'order_type' => CommonOrder::TYPE_MALL, 'is_delete' => 0, 'status' => 0]);
            //没发货之前都可以取消
            if ($order->user_coupon_id) {
                $userCoupon = UserCoupon::findOne($order->user_coupon_id);
                if ($userCoupon) {
                    $coupon = Coupon::findOne($userCoupon->coupon_id);
                    if ($coupon) {
                        $userCoupon->status = 0;
                        $userCoupon->price = $coupon->price;
                        $userCoupon->discount = $coupon->discount;
                        $userCoupon->type = $coupon->type;
                        $userCoupon->is_goods_limit = $coupon->is_goods_limit;
                        $userCoupon->save();
                    }
                }
            }
            if ($order->is_use_integral) {
                IntegralLogHelper::add($order->user_id, $order->use_integral, '订单:' . $order->order_no . '取消');
            }
            $commonOrder->status = CommonOrder::STATUS_INVALID;
            $commonOrder->invalid();
        }
        //订单完成
        if ($order->status == Order::STATUS_IS_COMPLETE) {
            $list = CommonOrder::find()->where(['order_id' => $this->order_id, 'is_delete' => 0, 'order_type' => CommonOrder::TYPE_MALL])->all();
            foreach ($list as $cOrder) {
                /**
                 * @var CommonOrder $cOrder ;
                 */
                $cOrder->status = 1;
                $cOrder->valid();
            }
        }
        //订单支付
        if ($order->status == Order::STATUS_NOT_SEND) {
            CommonOrder::updateAll(['is_pay' => 1, 'pay_time' => $order->pay_time], ['order_id' => $this->order_id, 'order_type' => CommonOrder::TYPE_MALL, 'is_delete' => 0]);
        }

        //订单发货
        if ($order->status == Order::STATUS_NOT_CONFIRM) {//发了货
            $mall_setting = OptionHelper::get('mall_setting', $order->mall_id);
            $time = 0;
            if ($mall_setting && $mall_setting['confirm_time']) {
                $time = $mall_setting['confirm_time'];
            }
            \Yii::$app->queue->delay($time*24*60)->push(new AutoConfirmJob(['order_id' => $this->order_id]));
        }

        //订单收货
        if ($order->status == Order::STATUS_IS_CONFIRM) {//收了货
            $mall_setting = OptionHelper::get('mall_setting', $order->mall_id);
            $time = 0;
            if ($mall_setting && $mall_setting['complete_time']) {
                $time = $mall_setting['complete_time'];
            }
            \Yii::$app->queue->delay($time * 24 * 60 * 60)->push(new OrderCompleteJob(['order_id' => $this->order_id]));
        }


    }
}
