<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2022-05-29
 * Time: 0:36
 */

namespace app\components;

use joinpay\entity\OrderQuery;
use joinpay\entity\SinglePay;
use joinpay\entity\UniPay;
use joinpay\JoinPayClient;
use yii\base\Component;
use yii\helpers\Json;

class JoinPay extends Component
{

    public function pay($params)
    {
        $pay_map = ['H5' => 'WEIXIN_GZH', 'MPWX' => 'WEIXIN_XCX', 'APP' => 'WEIXIN_APP3'];
        $params['frp_code'] = $pay_map[\Yii::$app->platform];
        $setting = \Yii::$app->params['wechatPaymentConfig'];
        $config = [
            // 商户ID
            'app_id' => $setting['app_id'], // 商户加密密钥
            'app_secret' => $setting['join_app_secret'], // 报备商户号
            'private_key' => $setting['join_private_key'], // 商户私钥
            'trade_merchantNo' => $setting['join_trade_merchant_no'], // 报备商户号
            'merchant_no' => $setting['join_merchant_no'],//
        ];
        $joinPayClient = JoinPayClient::getInstance($config);
        /**
         * @var UniPay $joinPayClient ;
         */
        $joinPayClient = $joinPayClient->driver('UniPay');// 使用驱动方式重新构造订单查询
        $res = $joinPayClient
            ->setVerison('1.0')// 版本号
            ->setOrderNo($params['order_no'])//
            ->setAmount($params['price'])
            ->setFrpCode($params['frp_code'])
            ->setProductName($params['goods_name'])
            ->setProductDesc($params['desc'])
            ->setNotifyUrl($params['notify_url'])
            ->setOpenId($params['open_id'])
            ->send();

        if ($res && $res['ra_Code']==100) {
            $res = Json::decode($res['rc_Result']);
            $res['timestamp'] = $res['timeStamp'];
            unset($res['timeStamp']);
            return $res;
        }

        throw new \yii\base\Exception($res['rb_CodeMsg']);
    }


    public function queryOrder($order_no)
    {
        $setting = \Yii::$app->params['wechatPaymentConfig'];
        $config = [
            // 商户ID
            'app_id' => $setting['app_id'], // 商户加密密钥
            'app_secret' => $setting['join_app_secret'], // 报备商户号
            'private_key' => $setting['join_private_key'], // 商户私钥
            'trade_merchantNo' => $setting['join_trade_merchant_no'], // 报备商户号
            'merchant_no' => $setting['join_merchant_no'],//
        ];
        $joinPayClient = JoinPayClient::getInstance($config);
        /**
         * @var OrderQuery $joinPayClient ;
         */
        $joinPayClient = $joinPayClient->driver('OrderQuery');// 使用驱动方式重新构造订单查询
        $res = $joinPayClient
            ->setOrderNo($order_no)// 订单号
            ->send();
        return $res;
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/23
     * Time：10:04
     * Note：单笔代付
     */
    public function singlePay($params)
    {
        $setting = \Yii::$app->params['wechatPaymentConfig'];
        $config = [
            // 商户ID
            'app_id' => $setting['app_id'], // 商户加密密钥
            'app_secret' => $setting['join_app_secret'], // 报备商户号
            'private_key' => $setting['join_private_key'], // 商户私钥
            'trade_merchantNo' => $setting['join_trade_merchant_no'], // 报备商户号
            'merchant_no' => $setting['join_merchant_no'],//
        ];
        $joinPayClient = JoinPayClient::getInstance($config);
        $joinPayClient->is_sort = false;
        /**
         * @var SinglePay $client ;
         */

        $client = $joinPayClient->driver('SinglePay');// 使用驱动方式重新构造订单查询
        $arr = [
            "userNo" => $config['merchant_no'],
            "productCode" => "BANK_PAY_ORDINARY_ORDER",
            "requestTime" => "",
            "merchantOrderNo" => "",
            "receiverAccountNoEnc" => "",
            "receiverNameEnc" => "",
            "receiverAccountType" => "201",
            "receiverBankChannelNo" => "",
            "paidAmount" => "",
            "currency" => "201",
            "isChecked" => "202",
            "paidDesc" => "",
            "paidUse" => "201",
            "callbackUrl" => ""
        ];
        foreach ($arr as $k => $item) {
            if (isset($params[$k]) && $params[$k]) {
                $arr[$k] = $params[$k];
            }
        }
        $client->setParams($arr);
        return $client->send(true, false, 'POST');
    }

}
