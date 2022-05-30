<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2022-05-26
 * Time: 19:31
 */

namespace app\controllers\notify;


use app\controllers\BaseController;
use app\helpers\WechatHelper;
use app\models\RefundOrderLog;
use app\models\WechatOrder;
use app\mongo\JxmallOption;
use yii\db\Exception;

class RefundController extends BaseController
{

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        \Yii::warning('退款回调');
        $data = $this->request->get();
        \Yii::warning($data);
        if ($data && is_array($data)) {
            if (isset($data['r2_OrderNo'])) {
                //是汇聚的订单
                $order_no = $data['r2_OrderNo'];
                $wechatOrder = WechatOrder::findOne(['order_no' => $order_no]);
                if ($wechatOrder) {
                    $exist = RefundOrderLog::find()->andWhere(['refund_order_no' => $data['r3_RefundOrderNo'], 'status' => 1])->exists();
                    if (!$exist) {
                        if ($data['ra_Status'] == 100) {
                            $log = new RefundOrderLog();
                            $log->order_no = $order_no;
                            $log->status = 1;
                            $log->refund_order_no = $data['r3_RefundOrderNo'];
                            $log->refund_price = $data['r4_RefundAmount_str'];
                            $log->mall_id = $wechatOrder->mall_id;
                            $log->from_pay_type = WechatOrder::PAY_JOINPAY;
                            $log->remark = $data['r8_ReceiveAccountNo'];
                            if (!$log->save()) {
                                \Yii::warning($log->getFirstErrors());
                            }
                        }
                        if ($data['ra_Status'] == 101) {
                            $log = new RefundOrderLog();
                            $log->order_no = $order_no;
                            $log->status = 2;
                            $log->refund_order_no = $data['r3_RefundOrderNo'];
                            $log->refund_price = $data['r4_RefundAmount_str'];
                            $log->mall_id = $wechatOrder->mall_id;
                            $log->from_pay_type = WechatOrder::PAY_JOINPAY;
                            $log->remark = $data['r8_ReceiveAccountNo'];
                            $log->message = $data['rc_CodeMsg'];
                            if (!$log->save()) {
                                \Yii::warning($log->getFirstErrors());
                            }
                            //如果是失败
                        }
                        return 'success';
                    }
                }

                return 'success';
            }

        }
        $xml = \Yii::$app->request->rawBody;
        if (!$xml) {
            return '回调数据为空';
        }
        $res = WechatHelper::xmlToArray($xml);
        \Yii::warning($res);
        if ($res && $res['mch_id']) {
            $payment = JxmallOption::findOne(['name' => $res['mch_id']]);
            if (!$payment) {
                return;
            }
            $payment = $payment->value;
            \Yii::$app->params['wechatPaymentConfig'] = [
                'app_id' => $payment['wechat_app_id'],//如果是app端这里要换成APP的支付
                'mch_id' => $payment['wechat_mch_id'],
                'key' => $payment['wechat_pay_secret'],// API 密钥
                // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
                'cert_path' => $payment['wechat_cert_pem_path'],    // XXX: 绝对路径！！！！
                'key_path' => $payment['wechat_key_pem_path'], // XXX: 绝对路径！！！！
                'notify_url' => '',     // 你也可以在下单时单独设置来想覆盖它
                /*************************************/
                'wechat_pay_type' => $payment['wechat_pay_type'] ?? 'original',
                // 商户加密密钥
                'join_app_secret' => $payment['join_app_secret'] ?? '',
                // 商户私钥
                'join_private_key' => $payment['join_private_key'] ?? '',
                // 报备商户号
                'join_trade_merchant_no' => $payment['join_trade_merchant_no'] ?? '',
                'join_merchant_no' => $payment['join_merchant_no'] ?? '',
            ];
            $app = \Yii::$app->wechat->payment;
            $response = $app->handleRefundedNotify(function ($message, $reqInfo, $fail) {
                \Yii::warning($reqInfo);
                if ($reqInfo['refund_status'] == 'SUCCESS') {
                    $order_no = $reqInfo['out_trade_no'];
                    $wechatOrder = WechatOrder::findOne(['order_no' => $order_no]);
                    if ($wechatOrder) {
                        try {
                            $log = RefundOrderLog::findOne(['refund_order_no' => $reqInfo['out_refund_no']]);
                            if ($log) {
                                return true;
                            }
                            $log = new RefundOrderLog();
                            $log->order_no = $order_no;
                            $log->refund_order_no = $reqInfo['out_refund_no'];
                            $log->refund_price = $reqInfo['settlement_refund_fee'] / 100;
                            $log->mall_id = $wechatOrder->mall_id;
                            $log->status=1;
                            $log->from_pay_type = WechatOrder::PAY_WECHAT;
                            $log->remark = $reqInfo['refund_recv_accout'];
                            if (!$log->save()) {
                                \Yii::warning($log->getFirstErrors());
                            }
                        } catch (Exception $exception) {
                            \Yii::warning($exception->getMessage());
                        }
                        return true;
                    }
                }
                return true; // 返回 true 告诉微信“我已处理完成”
                // 或返回错误原因 $fail('参数格式校验错误');
            });
            $response->send();
            return;
        }
    }
}