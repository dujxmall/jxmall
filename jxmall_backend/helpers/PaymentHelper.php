<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-22
 * Time: 0:17
 */

namespace app\helpers;


use app\core\ApiCode;
use app\models\OrderRefund;
use app\models\PayFlow;
use app\models\User;
use app\models\UserInfo;
use app\models\WechatOrder;
use joinpay\entity\UniPay;
use joinpay\JoinPayClient;
use yii\base\Exception;
use yii\helpers\Json;

class PaymentHelper
{

    public static function payment($mall_id)
    {
        $payment = OptionHelper::get('payment', $mall_id);

        if (!$payment) {
            return [];
        }
        if ($payment['wechat_status'] == 1) {
            $res['wechat_pay'] = [
                'payment_name' => '微信支付',
                'payment' => 'WECHAT_PAY',
                'icon' => $payment['wechat_icon']
            ];
        }
        if ($payment['balance_status'] == 1) {
            $res['balance_pay'] = [
                'payment_name' => '余额支付',
                'payment' => 'BALANCE_PAY',
                'icon' => $payment['balance_icon']
            ];

        }
        return $res;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-10-28
     * @Time: 20:54
     * @Note:
     * @param $order_no
     * @param $refund_no
     * @param $pay_price
     * @param $refund_price
     * @param $refund_desc
     * @param $mall_id
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public static function refund($order_no, $refund_no, $pay_price, $refund_price, $refund_desc, $mall_id)
    {
        WechatHelper::init($mall_id);
        $payment = \Yii::$app->wechat->payment;
        $res = $payment->refund->byOutTradeNumber($order_no, $refund_no, pay_fee($pay_price), pay_fee($refund_price), [
            'refund_desc' => $refund_desc,
        ]);
        if (is_array($res) && $res['result_code'] == 'SUCCESS') {
            $refund = OrderRefund::findOne(['order_no' => $refund_no]);
            if ($refund) {
                PayFlow::saveFlow($pay_price, $order_no, OrderRefund::class, $res['transaction_id'], $refund->user_id, $mall_id, PayFlow::TYPE_OUT, PayFlow::WECHAY_PAY);
            }

        }
        return $res;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-10-28
     * @Time: 20:53
     * @Note:
     * @param $order_no
     * @param $pay_price
     * @param $text
     * @param $notify_url
     * @param $user_id
     * @param $mall_id
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return  ['pay_data'=>[],'code'=>0,'msg'=>'']
     */
    public static function pay($order_no, $pay_price, $text, $user_id, $mall_id, $class)
    {
        WechatHelper::init($mall_id);
        $notify_url = '/web/notify/index.php';
        $notify_url = ServerHelper::getHost() . $notify_url;
        $user = User::getOne($user_id);
        if (!$user) {
            return ['code' => ApiCode::CODE_FAIL, 'msg' => '支付失败'];
        }
        $openid = $user->openid;

        $order = WechatOrder::findOne(['order_no' => $order_no, 'is_delete' => 0]);
        if (!$order) {
            $order = new WechatOrder();
            $order->mall_id = $mall_id;
        }

        if (\Yii::$app->params['wechatPaymentConfig']['wechat_pay_type'] == 'joinpay') {
            try {
                $res = \Yii::$app->joinPay->pay([
                        'order_no' => $order_no,
                        'goods_name' => $text,
                        'price' => $pay_price,
                        'open_id' => $user->openid,
                        'notify_url' => $notify_url]
                );
            } catch (Exception $e) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, $e->getMessage());
            }
            $order->order_no = $order_no;
            $order->pay_type = WechatOrder::PAY_JOINPAY;
            $order->pay_price = $pay_price;
            $order->class = $class;
            $order->platform = \Yii::$app->platform;
            $order->save();
            return ['pay_data' => $res, 'code' => ApiCode::CODE_SUCCESS, 'msg' => '调用汇聚支付成功'];
        }
        $payment = \Yii::$app->wechat->payment;
        if (\Yii::$app->platform == ConstantHelper::PLATFORM_H5) {
            $trade_type = 'JSAPI';
        }
        if (\Yii::$app->platform == ConstantHelper::PLATFORM_MPWX) {
            $trade_type = 'JSAPI';
        }
        if (\Yii::$app->platform == ConstantHelper::PLATFORM_APP) {
            $trade_type = 'APP';
        }
        if (\Yii::$app->platform != ConstantHelper::PLATFORM_APP) {
            $orderData = [
                'body' => $text,
                'out_trade_no' => $order_no,
                'total_fee' => pay_fee($pay_price),
                'trade_type' => $trade_type, // 请对应换成你的支付方式对应的值类型
                'openid' => $openid,
                'notify_url' => $notify_url,
                'attach' => $mall_id
            ];
        } else {
            $orderData = [
                'body' => $text,
                'out_trade_no' => $order_no,
                'total_fee' => pay_fee($pay_price),
                'trade_type' => $trade_type, // 请对应换成你的支付方式对应的值类型
                'notify_url' => $notify_url,
                'attach' => $mall_id
            ];
        }
        $result = $payment->order->unify($orderData);
        \Yii::warning($result);
        //code 0 正常
        if ($result['return_code'] == 'SUCCESS') { //表示通信成功
            if ($result['result_code'] != 'FAIL') {
                $prepayId = $result['prepay_id'];
                if (\Yii::$app->platform == ConstantHelper::PLATFORM_APP) {
                    $config = $payment->jssdk->appConfig($prepayId);
                } else {
                    $config = $payment->jssdk->sdkConfig($prepayId);
                }
                $order->order_no = $order_no;
                $order->pay_type = WechatOrder::PAY_WECHAT;
                $order->pay_price = $pay_price;
                $order->class = $class;
                $order->platform = \Yii::$app->platform;
                $order->save();
                return ['pay_data' => $config, 'code' => ApiCode::CODE_SUCCESS, 'msg' => $result['err_code']];
            }
            if ($result['result_code'] == 'FAIL') {
                return ['code' => ApiCode::CODE_FAIL, 'msg' => $result['err_code_des']];
            }
        } else {
            return ['code' => ApiCode::CODE_FAIL, 'msg' => $result['return_msg']];
        }
        return ['code' => ApiCode::CODE_FAIL, 'msg' => '支付失败'];
    }

}
