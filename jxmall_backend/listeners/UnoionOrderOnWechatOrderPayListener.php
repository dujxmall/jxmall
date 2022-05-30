<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/26
 * Time: 12:51
 * Note:
 */

namespace app\listeners;


use app\core\PaymentType;
use app\helpers\LockHelper;
use app\models\CommonOrder;
use app\models\PayFlow;
use app\models\UnionOrder;
use app\models\WechatOrder;

class UnoionOrderOnWechatOrderPayListener extends BaseListener
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
        \Yii::$app->on(WechatOrder::EVENT_PAY, function ($event) {
            $wechatOrder = WechatOrder::getOne($event->order_id);
            if (!$wechatOrder || !$wechatOrder->is_pay) {
                return;
            }
            if ($wechatOrder->class != UnionOrder::class) {
                return;
            }
            \Yii::warning('统一订单支付');
            $unionOrder = UnionOrder::findOne(['union_no' => $wechatOrder->order_no, 'is_pay' => 0, 'mall_id' => $wechatOrder->mall_id]);
            if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                $unionOrder->is_pay = 1;
                $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                $unionOrder->save();
                CommonOrder::updateAll(['platform' => $wechatOrder->platform], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $wechatOrder->transaction_id, $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
        });

    }
}