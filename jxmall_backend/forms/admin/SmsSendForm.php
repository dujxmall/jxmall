<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-25
 * Time: 20:57
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\OptionHelper;
use app\models\BaseModel;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsSendForm extends BaseModel
{

    public $mobile;


    public function rules()
    {
        return [
            [['mobile'], 'integer'],
            [['mobile'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function sendAuthCode()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $sys_auth_code = CacheHelper::get('sys_auth_code' . $this->mobile);
        if ($sys_auth_code) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '请勿频繁发送验证码');
        }

        $sms_setting = OptionHelper::get('sys_sms_setting');
        if (!$sms_setting) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '未配置短信');
        }
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'yunpian' => [
                    'api_key' => '',
                ],
                'aliyun' => [
                    'access_key_id' => $sms_setting['access_key_id'],
                    'access_key_secret' => $sms_setting['access_key_secret'],
                    'sign_name' => $sms_setting['sign_name'],
                ],
                //...
            ],
        ];

        $code = mt_rand(1001, 9999);
        $easySms = new EasySms($config);
        try {
            $result = $easySms->send($this->mobile, [
                'content' => '您的验证码为: ' . $code,
                'template' => $sms_setting['auth_code_tpl'],
                'data' => [
                    'code' => $code
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '参数错误');
        } catch (NoGatewayAvailableException $e) {
            return $this->apiResponse(ApiCode::CODE_FAIL, (($e->results)['aliyun'])['exception']->getMessage());
        }
        $res = $result['aliyun'];
        if ($res['status'] == 'success') {
            CacheHelper::set('sys_auth_code' . $this->mobile, $code, 180);

            return $this->apiResponse(ApiCode::CODE_SUCCESS, '发送成功');
        }
        return $this->apiResponse(ApiCode::CODE_FAIL, '发送失败');
    }


}