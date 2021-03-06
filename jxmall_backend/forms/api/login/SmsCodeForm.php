<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/4
 * Time: 9:48
 * Note:
 */

namespace app\forms\api\login;


use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsCodeForm extends BaseModel
{

    public $mobile;


    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['mobile'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号'
        ]; // TODO: Change the autogenerated stub
    }

    public function getCode()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $code = CacheHelper::get('sms_code' . $this->mobile);
        if ($code) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '请勿频繁获取验证码!');
        }
        /*CacheHelper::set('sms_code' . $this->mobile, 123456, 180);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '发送成功');*/
        $code = mt_rand(100000, 999999);
        //发送短信
        CacheHelper::set('sms_code' . $this->mobile, $code, 180);
        $sms_setting = OptionHelper::get('mall_setting', $this->mall_id);
        if (!$sms_setting) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '未配置短信');
        }
        $config = [
            'timeout' => 5.0,
            'default' => [
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
                'gateways' => [
                    'aliyun',
                ],
            ],
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
            ],
        ];

        $easySms = new EasySms($config);
        try {
            $result = $easySms->send($this->mobile, [
                'content' => '您的验证码为: ' . $code,
                'template' => $sms_setting['sms_tpl_code'],
                'data' => [
                    $sms_setting['sms_tpl'] => $code
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\InvalidArgumentException $e) {
            CacheHelper::remove('sms_code' . $this->mobile);
            return $this->apiResponse(ApiCode::CODE_FAIL, '参数错误');
        } catch (NoGatewayAvailableException $e) {
            CacheHelper::remove('sms_code' . $this->mobile);
            $e = $e->getExceptions()['aliyun'];
            \Yii::warning($e->getMessage());
            return $this->apiResponse(ApiCode::CODE_FAIL, $e->getMessage());
        }
        $res = $result['aliyun'];
        if ($res['status'] == 'success') {
            CacheHelper::set('sms_code' . $this->mobile, $code, 180);
            return $this->apiResponse(ApiCode::CODE_SUCCESS, '发送成功');
        }
        CacheHelper::remove('sms_code' . $this->mobile);
        return ResponseHelper::send(ApiCode::CODE_FAIL, '验证码发送失败，请重试');
    }

}