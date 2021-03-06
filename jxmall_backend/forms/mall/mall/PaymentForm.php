<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-25
 * Time: 20:58
 */

namespace app\forms\mall\mall;


use app\core\ApiCode;
use app\helpers\IconHelper;
use app\helpers\OptionHelper;
use app\models\BaseModel;
use Exception;
use yii\helpers\FileHelper;

class PaymentForm extends BaseModel
{


    public $wechat_mch_id;
    public $wechat_pay_secret;
    public $wechat_cert_pem;
    public $wechat_key_pem;
    public $wechat_status;
    public $wechat_app_id;
    public $balance_status;
    public $wechat_key_pem_path;
    public $wechat_cert_pem_path;
    public $balance_icon;
    public $wechat_icon;
    public $wechat_serial_no;//证书序列号
    public $wechat_v3_key;//证书序列号
    public $wechat_pay_type;//original  joinpay
    public $join_trade_merchant_no;
    public $join_private_key;
    public $join_app_secret;
    public $join_merchant_no;


    public function rules()
    {
        return [
            [['wechat_status', 'balance_status'], 'integer'],
            [['wechat_mch_id', 'wechat_pay_secret', 'wechat_v3_key'], 'string', 'max' => 32],
            [['wechat_cert_pem', 'wechat_key_pem', 'wechat_key_pem_path', 'wechat_cert_pem_path', 'balance_icon', 'wechat_icon', 'wechat_pay_type','join_trade_merchant_no','join_private_key','join_app_secret','join_merchant_no'], 'string'],
            [['wechat_app_id', 'wechat_serial_no'], 'string', 'max' => 64],

        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        if ($this->wechat_status) {
            if ($this->wechat_cert_pem && $this->wechat_key_pem) {
                if (!is_writeable(\Yii::$app->runtimePath)) {
                    chmod(\Yii::$app->runtimePath, 0755);
                }
                $pemDir = \Yii::$app->runtimePath . '/pem';
                if (!is_dir($pemDir)) {
                    FileHelper::createDirectory($pemDir, 0755);
                }

                $certPemFile = $pemDir . '/' . md5($this->wechat_cert_pem . \Yii::$app->mall->id) . '.pem';
                file_put_contents($certPemFile, $this->wechat_cert_pem);
                $this->wechat_cert_pem_path = $certPemFile;
                $keyPemFile = $pemDir . '/' . md5($this->wechat_key_pem . \Yii::$app->mall->id) . '.pem';
                file_put_contents($keyPemFile, $this->wechat_key_pem);
                $this->wechat_key_pem_path = $keyPemFile;
            }
            // 检测参数是否有效
            if (!$this->wechat_app_id || !$this->wechat_mch_id || !$this->wechat_pay_secret) {
                return [
                    'code' => ApiCode::CODE_FAIL,
                    'msg' => '微信支付信息未完善，或关闭微信支付！'
                ];
            }
            $config = \Yii::$app->params['wechatPaymentConfig'];
            $config['app_id'] = $this->wechat_app_id;
            $config['mch_id'] = $this->wechat_mch_id;
            $config['key'] = $this->wechat_pay_secret;
            \Yii::$app->params['wechatPaymentConfig'] = $config;
            try {
                $payment = \Yii::$app->wechat->payment;
                $res = $payment->order->queryByOutTradeNumber('88888888');
                if ($res && $res['return_code'] == 'FAIL') {
                    return $this->apiResponse(ApiCode::CODE_FAIL, $res['return_msg']);
                }
            } catch (Exception $e) {
                return $this->apiResponse(ApiCode::CODE_FAIL, $e->getMessage());
            }
        }
        $res = OptionHelper::set('payment', $this->attributes, \Yii::$app->mall->id);
        if($this->wechat_mch_id){
            OptionHelper::set($this->wechat_mch_id,$this->attributes,$this->mall_id);
        }
        if (!$res) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '保存失败');
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function search()
    {
        $res = OptionHelper::get('payment', \Yii::$app->mall->id);
        if (!$res) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '未配置支付', ['wechat_icon' => IconHelper::wechat(), 'balance_icon' => IconHelper::balance()]);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => $res]);
    }
}