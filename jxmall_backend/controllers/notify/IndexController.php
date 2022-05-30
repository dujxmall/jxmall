<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/26
 * Time: 11:05
 * Note:
 */

namespace app\controllers\notify;


use app\controllers\BaseController;
use app\helpers\LockHelper;
use app\helpers\WechatHelper;
use app\models\WechatOrder;
use EasyWeChat\Kernel\Exceptions\Exception;

class IndexController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        \Yii::$app->isAdmin=false;
        if ($this->pay_from == 'joinpay') {
            \Yii::warning('汇聚支付');
            $message = $this->request->get();
            $order_no = $message['r2_OrderNo'];
            $order = WechatOrder::findOne(['order_no' => $order_no, 'is_pay' => 0, 'is_delete' => 0]);
            if ($order) {
                if (LockHelper::setWechatOrder($order->id)) {
                    //订单完成支付，每个地方需要更新订单得地方自己更新这里触发订单支付事件
                    $order->is_pay = 1;
                    $order->transaction_id = $message['r9_BankTrxNo'];
                    $order->pay_time = date('Y-m-d H:i:s', time());
                    if (!$order->save()) {
                        \Yii::warning($order->getFirstErrors());
                    }
                }
                \Yii::warning('微信订单支付完成');
            }
            return 'success';
        }

        \Yii::$app->response->headers->add('Content-Type', 'text/xml');
        if ($this->pay_from == 'wechat') {
            \Yii::warning($this->wechat_result);
            $res = $this->wechat_result;
            if ($res && isset($res['out_trade_no'])) {
                $order_no = $res['out_trade_no'];
                $order = WechatOrder::findOne(['order_no' => $order_no, 'is_delete' => 0]);
                if (!$order) {
                    \Yii::warning('订单不存在');
                    \Yii::$app->response->content = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                    \Yii::$app->response->send();
                    return;
                }
                if ($order->is_pay == 1) {
                    \Yii::warning('订单已支付，不要再通知我');
                    \Yii::$app->response->content = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                    \Yii::$app->response->send();
                    return;
                }
                if (LockHelper::setWechatOrder($order->id)) {
                    \Yii::$app->platform = $order->platform;
                    WechatHelper::init($this->mall_id);
                    $app = \Yii::$app->wechat;
                    try {
                        $response = $app->payment->handlePaidNotify(function ($message, $fail) use ($order) {
                            if ($order->is_pay) {
                                \Yii::warning('订单已完成支付');
                                return true;
                            }
                            try {
                                if ($message['return_code'] !== 'SUCCESS' && $message['result_code'] !== 'SUCCESS') {
                                    \Yii::error('pay_notify 订单尚未支付: ' . $message['result_code']);
                                    return $fail('通信失败，请稍后再通知我');
                                }
                                if ($order) {
                                    //订单完成支付，每个地方需要更新订单得地方自己更新这里触发订单支付事件
                                    $order->is_pay = 1;
                                    $order->transaction_id = $message['transaction_id'];
                                    $order->pay_time = date('Y-m-d H:i:s', time());
                                    if (!$order->save()) {
                                        \Yii::warning($order->getFirstErrors());
                                    }
                                    \Yii::warning('微信订单支付完成');
                                }
                            } catch (\Exception $e) {

                                \Yii::warning($e);
                            }
                            return true;
                        });
                    } catch (Exception $e) {
                        \Yii::warning($e->getMessage());
                    }
                    $response->send();
                }

            }

        }
        \Yii::$app->response->content = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        \Yii::$app->response->send();
        return;
    }
}