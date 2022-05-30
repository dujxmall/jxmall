<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-25
 * Time: 21:50
 */

namespace app\helpers;


use app\core\ApiCode;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Client;

class WechatHelper
{

    public static function init($mall_id)
    {
        $wechat = OptionHelper::get('platform_wechat', $mall_id);
        if ($wechat) {
            \Yii::$app->params['wechatConfig'] = [
                'app_id' => $wechat['appid'],         // AppID
                'secret' => $wechat['secret'],     // AppSecret
                'token' => $wechat['token'],          // Token
                'aes_key' => $wechat['aes_key'],     // EncodingAESKey，兼容与安全模式下请一定要填写！！！
                /**
                 * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
                 * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
                 */
                'response_type' => 'array',
            ];
        }
        $mpwx = OptionHelper::get('platform_mpwx', $mall_id);
        $wxapp = OptionHelper::get('platform_wxapp', $mall_id);
        $payment = OptionHelper::get('payment', $mall_id);
        if (\Yii::$app->platform == ConstantHelper::PLATFORM_MPWX) {
            if ($mpwx) {
                $payment['wechat_app_id'] = $mpwx['appid'];
            }
        }
        if (\Yii::$app->platform == ConstantHelper::PLATFORM_APP) {
            if ($wxapp) {
                $payment['wechat_app_id'] = $wxapp['appid'];
            }
        }

        if ($payment && $payment['wechat_status'] == 1) {
            \Yii::$app->params['wechatPaymentConfig'] = [
                'app_id' => $payment['wechat_app_id'],//如果是app端这里要换成APP的支付
                'mch_id' => $payment['wechat_mch_id'],
                'key' => $payment['wechat_pay_secret'],// API 密钥
                // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
                'cert_path' => $payment['wechat_cert_pem_path'],    // XXX: 绝对路径！！！！
                'key_path' => $payment['wechat_key_pem_path'], // XXX: 绝对路径！！！！
                'notify_url' => '',     // 你也可以在下单时单独设置来想覆盖它
               /*************************************/
                'wechat_pay_type'=>$payment['wechat_pay_type']??'original',
                // 商户加密密钥
                'join_app_secret' => $payment['join_app_secret']??'',
                // 商户私钥
                'join_private_key' => $payment['join_private_key']??'',
                // 报备商户号
                'join_trade_merchant_no' => $payment['join_trade_merchant_no']??'',
                'join_merchant_no' => $payment['join_merchant_no']??'',
            ];

        }

        if ($mpwx) {
            \Yii::$app->params['wechatMiniProgramConfig'] = [
                'app_id' => $mpwx['appid'],         // AppID
                'secret' => $mpwx['secret'],     // AppSecret
            ];
        }
        return true;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 0:49
     * @Note:告诉微信已经成功了
     * @return mixed
     */
    public static function success()
    {
        return ArrayHelper::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 0:50
     * @Note:告诉微信失败了
     * @return mixed
     */
    public static function fail()
    {
        return ArrayHelper::toXml(['return_code' => 'FAIL', 'return_msg' => 'OK']);
    }

    /**
     * 数组转换成XML数据
     * @param $array
     * @return string
     * @throws PaymentException
     */
    public static function arrayToXml($array)
    {
        if (!is_array($array)) {
            throw new Exception('`$arr`不是有效的array。');
        }
        $xml = "<xml>\r\n";
        $xml .= self::arrayToXmlSub($array);
        $xml .= "</xml>";
        return $xml;
    }

    private static function arrayToXmlSub($array)
    {
        if (!is_array($array)) {
            throw new Exception('`$array`不是有效的array。');
        }
        $xml = "";
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                if (is_numeric($key)) {
                    $xml .= self::arrayToXmlSub($val);
                } else {
                    $xml .= "<" . $key . ">" . self::arrayToXmlSub($val) . "</" . $key . ">\r\n";
                }
            } elseif (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">\r\n";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">\r\n";
            }
        }
        return $xml;
    }

    /**
     * 是否是xml
     * @param $str
     * @return bool|mixed
     */
    public static function isXmlString($str)
    {
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $str, true)) {
            xml_parser_free($xml_parser);
            return false;
        } else {
            return (json_decode(json_encode(simplexml_load_string($str)), true));
        }
    }

    public static function xmlToArray($xml)
    {
        $res = [];
        if (self::isXmlString($xml)) {
            // 禁止引用外部xml实体

            if (PHP_VERSION_ID < 80000) {
                libxml_disable_entity_loader(true);
            }
            $res = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return (array)$res;
    }

    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/22
     * Time：13:11
     * Note：商户平台接口封装
     */
    public static function post($url, $body, $mall_id)
    {
        $mpwx = OptionHelper::get('platform_mpwx', $mall_id);
        if (!$mpwx) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '未配置小程序');
        }
        $payment = OptionHelper::get('payment', $mall_id);
        if (!$mpwx) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '为配置微信支付信息');
        }
        $url_parts = parse_url($url);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $timestamp = time();
        $nonce = uniqid();
        if (is_array($body)) {
            $body = Json::encode($body);
        }
        $message = 'POST' . "\n" .
            $canonical_url . "\n" .
            $timestamp . "\n" .
            $nonce . "\n" .
            $body . "\n";
        $mch_private_key = $payment['wechat_key_pem'];
        openssl_sign($message, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);
        $serial_no = $payment['wechat_serial_no'];
        $schema = 'WECHATPAY2-SHA256-RSA2048';
        $token = sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $payment['wechat_mch_id'], $nonce, $timestamp, $serial_no, $sign);
        $token = $schema . " " . $token;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        try {
            $response = $client->createRequest()
                ->setUrl($url)
                ->setHeaders(['Authorization' => $token, 'Content-Type' => 'application/json', 'Accept' => 'application/json', 'User-Agent' => $_SERVER['HTTP_USER_AGENT']])
                ->setMethod('POST')
                ->setContent($body)->send();
        } catch (InvalidConfigException $e) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, $e->getMessage());
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['result' => $response->content]);
    }
    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/4/22
     * Time：13:11
     * Note：商户平台接口封装
     */
    public static function get($url, $body = '', $mall_id = 1)
    {
        $mpwx = OptionHelper::get('platform_mpwx', $mall_id);
        if (!$mpwx) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '未配置小程序');
        }
        $payment = OptionHelper::get('payment', $mall_id);
        if (!$mpwx) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '为配置微信支付信息');
        }
        $url_parts = parse_url($url);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $timestamp = time();
        $nonce = uniqid();
        if (is_array($body)) {
            $body = Json::encode($body);
        }
        $message = 'GET' . "\n" .
            $canonical_url . "\n" .
            $timestamp . "\n" .
            $nonce . "\n" .
            $body . "\n";
        $mch_private_key = $payment['wechat_key_pem'];
        openssl_sign($message, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);
        $serial_no = $payment['wechat_serial_no'];
        $schema = 'WECHATPAY2-SHA256-RSA2048';
        $token = sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $payment['wechat_mch_id'], $nonce, $timestamp, $serial_no, $sign);
        $token = $schema . " " . $token;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        try {
            $response = $client->createRequest()
                ->setUrl($url)
                ->setHeaders(['Authorization' => $token, 'Content-Type' => 'application/json', 'Accept' => 'application/json', 'User-Agent' => $_SERVER['HTTP_USER_AGENT']])
                ->setMethod('GET')
                ->setContent($body)->send();
        } catch (InvalidConfigException $e) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, $e->getMessage());
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['result' => $response->content]);
    }
}
