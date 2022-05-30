<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-27
 * Time: 23:04
 */

namespace app\controllers\notify;


use app\controllers\BaseController;
use app\core\PaymentType;
use app\helpers\ConstantHelper;
use app\helpers\LockHelper;
use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\helpers\ServerHelper;
use app\helpers\WechatHelper;
use app\models\CommonOrder;
use app\models\PayFlow;
use app\models\UnionOrder;
use app\mongo\JxmallOption;

class OrderController extends BaseController
{
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        \Yii::$app->isAdmin = false;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-27
     * @Time: 23:05
     * @Note:合并下单
     * H5
     */
    public function actionUnionH5()
    {
        \Yii::$app->platform = ConstantHelper::PLATFORM_H5;
        WechatHelper::init($this->mall_id);
        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            \Yii::warning($message);
            $order_no = $message['r2_OrderNo'];
            $unionOrder = UnionOrder::findOne(['union_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
            if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                $unionOrder->is_pay = 1;
                $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                $unionOrder->save();
                CommonOrder::updateAll(['platform' => ConstantHelper::PLATFORM_H5], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $message['r7_TrxNo'], $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
            return 'success';
        }

        $app = \Yii::$app->wechat;
        $response = $app->payment->handlePaidNotify(function ($message, $fail) {
            \Yii::warning($message);
            try {
                $res = $message;
                if ($res['return_code'] !== 'SUCCESS' && $res['result_code'] !== 'SUCCESS') {
                    \Yii::error('pay_notify 订单尚未支付: ' . $res['result_code']);
                    return $fail('通信失败，请稍后再通知我');
                }
                $order_no = $res['out_trade_no'];
                $unionOrder = UnionOrder::findOne(['union_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
                if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                    $unionOrder->is_pay = 1;
                    $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                    $unionOrder->save();
                    CommonOrder::updateAll(['platform' => ConstantHelper::PLATFORM_H5], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                    PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $message['transaction_id'], $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
                }
            } catch (\Exception $e) {

                \Yii::warning(OrderController::class . ':' . SerializeHelper::encode($e->getMessage()));
            }
            return true;
        });
        $response->send();
    }


    public function actionUnionMpwx()
    {
        \Yii::$app->platform = ConstantHelper::PLATFORM_MPWX;
        WechatHelper::init($this->mall_id);
        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            \Yii::warning($message);
            $order_no = $message['r2_OrderNo'];
            $unionOrder = UnionOrder::findOne(['union_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
            if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                $unionOrder->is_pay = 1;
                $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                $unionOrder->save();
                CommonOrder::updateAll(['platform' => ConstantHelper::PLATFORM_H5], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $message['r7_TrxNo'], $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
            return 'success';
        }

        $app = \Yii::$app->wechat;
        try {
            $response = $app->payment->handlePaidNotify(function ($message, $fail) {
                try {
                    $res = $message;
                    \Yii::warning($res);
                    if ($res['return_code'] !== 'SUCCESS' && $res['result_code'] !== 'SUCCESS') {
                        \Yii::error('pay_notify 订单尚未支付: ' . $res['result_code']);
                        return $fail('通信失败，请稍后再通知我');
                    }
                    $order_no = $res['out_trade_no'];
                    $unionOrder = UnionOrder::findOne(['union_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
                    if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                        $unionOrder->is_pay = 1;
                        $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                        $unionOrder->save();
                        CommonOrder::updateAll(['platform' => ConstantHelper::PLATFORM_MPWX], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                        PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $message['transaction_id'], $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
                    }
                } catch (\Exception $e) {

                    \Yii::warning(OrderController::class . ':' . SerializeHelper::encode($e->getMessage()));
                }
                return true;
            });
            $response->send();
        } catch (\Exception $exception) {


            \Yii::warning($exception->getMessage());
        }
    }


    public function actionUnionApp()
    {
        \Yii::warning('app支付');
        \Yii::$app->platform = ConstantHelper::PLATFORM_APP;
        WechatHelper::init($this->mall_id);

        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            \Yii::warning($message);
            $order_no = $message['r2_OrderNo'];
            $unionOrder = UnionOrder::findOne(['union_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
            if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                $unionOrder->is_pay = 1;
                $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                $unionOrder->save();
                CommonOrder::updateAll(['platform' => ConstantHelper::PLATFORM_H5], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $message['r7_TrxNo'], $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
            return 'success';
        }
        $app = \Yii::$app->wechat;
        try {
            $response = $app->payment->handlePaidNotify(function ($message, $fail) {
                try {
                    $res = $message;
                    if ($res['return_code'] !== 'SUCCESS' && $res['result_code'] !== 'SUCCESS') {
                        \Yii::error('pay_notify 订单尚未支付: ' . $res['result_code']);
                        return $fail('通信失败，请稍后再通知我');
                    }
                    $order_no = $res['out_trade_no'];
                    $unionOrder = UnionOrder::findOne(['union_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
                    if ($unionOrder && LockHelper::setUnionOrderPay($unionOrder->id)) {
                        $unionOrder->is_pay = 1;
                        $unionOrder->pay_type = PaymentType::TYPE_WECHAT;
                        $unionOrder->save();
                        $num = CommonOrder::updateAll(['platform' => ConstantHelper::PLATFORM_MPWX], ['union_no' => $unionOrder->union_no, 'is_delete' => 0]);
                        PayFlow::saveFlow($unionOrder->pay_price, $unionOrder->union_no, UnionOrder::class, $message['transaction_id'], $unionOrder->user_id, $unionOrder->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);

                    }
                } catch (\Exception $e) {
                    \Yii::warning(OrderController::class . ':' . SerializeHelper::encode($e->getMessage()));
                }
                return true;
            });
            $response->send();
        } catch (\Exception $exception) {

            \Yii::warning($exception->getMessage());
        }
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 1:22
     * @Note:普通下单 H5
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function actionCommonH5()
    {
        \Yii::warning('pay_notify 返回的数据: ');
        \Yii::$app->platform = ConstantHelper::PLATFORM_H5;
        WechatHelper::init($this->mall_id);
        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            \Yii::warning($message);
            $order_no = $message['r2_OrderNo'];
            $order = CommonOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
            if ($order && LockHelper::setCommonOrderPay($order->id)) {
                $order->is_pay = 1;
                $order->pay_type = PaymentType::TYPE_WECHAT;
                $order->platform = ConstantHelper::PLATFORM_H5;
                $order->pay_time = time();
                $order->save();
                PayFlow::saveFlow($order->pay_price, $order->order_no, CommonOrder::class, $message['r7_TrxNo'], $order->user_id, $order->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
            return 'success';
        }


        $app = \Yii::$app->wechat;
        $response = $app->payment->handlePaidNotify(function ($message, $fail) {
            try {
                $res = $message;
                if ($res['return_code'] !== 'SUCCESS' && $res['result_code'] !== 'SUCCESS') {
                    \Yii::error('pay_notify 订单尚未支付: ' . $res['result_code']);
                    return $fail('通信失败，请稍后再通知我');
                }
                $order_no = $res['out_trade_no'];


                $order = CommonOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
                if ($order && LockHelper::setCommonOrderPay($order->id)) {
                    $order->is_pay = 1;
                    $order->pay_type = PaymentType::TYPE_WECHAT;
                    $order->platform = ConstantHelper::PLATFORM_H5;
                    $order->pay_time = time();
                    $order->save();
                    PayFlow::saveFlow($order->pay_price, $order->order_no, CommonOrder::class, $message['transaction_id'], $order->user_id, $order->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);

                }
            } catch (\Exception $e) {
                \Yii::warning(OrderController::class . ':' . SerializeHelper::encode($e->getMessage()));
            }
            return true;
        });
        $response->send();
    }

    public function actionCommonMpwx()
    {
        \Yii::$app->platform = ConstantHelper::PLATFORM_MPWX;
        WechatHelper::init($this->mall_id);
        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            \Yii::warning($message);
            $order_no = $message['r2_OrderNo'];
            $order = CommonOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
            if ($order && LockHelper::setCommonOrderPay($order->id)) {
                $order->is_pay = 1;
                $order->pay_type = PaymentType::TYPE_WECHAT;
                $order->platform = ConstantHelper::PLATFORM_H5;
                $order->pay_time = time();
                $order->save();
                PayFlow::saveFlow($order->pay_price, $order->order_no, CommonOrder::class, $message['r7_TrxNo'], $order->user_id, $order->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
            return 'success';
        }
        $app = \Yii::$app->wechat;
        $response = $app->payment->handlePaidNotify(function ($message, $fail) {
            try {
                $res = $message;
                if ($res['return_code'] !== 'SUCCESS' && $res['result_code'] !== 'SUCCESS') {
                    \Yii::error('pay_notify 订单尚未支付: ' . $res['result_code']);
                    return $fail('通信失败，请稍后再通知我');
                }
                $order_no = $res['out_trade_no'];
                $order = CommonOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
                if ($order && LockHelper::setCommonOrderPay($order->id)) {
                    $order->is_pay = 1;
                    $order->pay_type = PaymentType::TYPE_WECHAT;
                    $order->platform = ConstantHelper::PLATFORM_MPWX;
                    $order->pay_time = time();
                    $order->save();
                    PayFlow::saveFlow($order->pay_price, $order->order_no, CommonOrder::class, $message['transaction_id'], $order->user_id, $order->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);

                }
            } catch (\Exception $e) {
                \Yii::warning(OrderController::class . ':' . SerializeHelper::encode($e->getMessage()));
            }
            return true;
        });
        $response->send();
    }

    public function actionCommonApp()
    {

        \Yii::warning('app支付');
        \Yii::$app->platform = ConstantHelper::PLATFORM_MPWX;
        WechatHelper::init($this->mall_id);
        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            \Yii::warning($message);
            $order_no = $message['r2_OrderNo'];
            $order = CommonOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
            if ($order && LockHelper::setCommonOrderPay($order->id)) {
                $order->is_pay = 1;
                $order->pay_type = PaymentType::TYPE_WECHAT;
                $order->platform = ConstantHelper::PLATFORM_H5;
                $order->pay_time = time();
                $order->save();
                PayFlow::saveFlow($order->pay_price, $order->order_no, CommonOrder::class, $message['r7_TrxNo'], $order->user_id, $order->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);
            }
            return 'success';
        }
        $app = \Yii::$app->wechat;

        $response = $app->payment->handlePaidNotify(function ($message, $fail) {
            try {
                $res = $message;
                if ($res['return_code'] !== 'SUCCESS' && $res['result_code'] !== 'SUCCESS') {
                    \Yii::error('pay_notify 订单尚未支付: ' . $res['result_code']);
                    return $fail('通信失败，请稍后再通知我');
                }
                $order_no = $res['out_trade_no'];
                $order = CommonOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'mall_id' => $this->mall_id]);
                if ($order && LockHelper::setCommonOrderPay($order->id)) {
                    $order->is_pay = 1;
                    $order->pay_type = PaymentType::TYPE_WECHAT;
                    $order->platform = ConstantHelper::PLATFORM_MPWX;
                    $order->pay_time = time();
                    $order->save();
                    PayFlow::saveFlow($order->pay_price, $order->order_no, CommonOrder::class, $message['transaction_id'], $order->user_id, $order->mall_id, PayFlow::TYPE_IN, PayFlow::WECHAY_PAY);

                }
            } catch (\Exception $e) {
                \Yii::warning(OrderController::class . ':' . SerializeHelper::encode($e->getMessage()));
            }
            return true;
        });
        $response->send();
    }

}