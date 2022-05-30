<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-05
 * Time: 0:28
 */

namespace app\forms\api\cash;


use app\core\ApiCode;
use app\helpers\BankHelper;
use app\helpers\IconHelper;
use app\helpers\OptionHelper;
use app\models\Bank;
use app\models\BaseModel;
use app\models\Cash;
use app\models\User;

class CashForm extends BaseModel
{
    public function getCashInfo()
    {
        $cash = Cash::find()->where(['user_id' => \Yii::$app->user->identity->id, 'status' => 0])->exists();
        if ($cash) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '存在未处理的提现申请!');
        }
        $setting = OptionHelper::get('cash_setting', \Yii::$app->mall->id);
        if (!$setting) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '系统未开启提现设置');
        }
        /**
         * @var User $user ;
         */
        $user = \Yii::$app->user->identity;
        $info = [
            'price' => $user->price,
            'money' => $user->money
        ];
        if ($setting['price']['is_cash']) {
            $cash_type = $setting['price']['cash_type'];
            $newCashType = [];
            foreach ($cash_type as $type) {
                $newItem = [];
                if ($type == 'bank') {
                    $newItem = ['cash_type' => 'bank', 'name' => '银行卡', 'icon' => IconHelper::bank()];
                }
                if ($type == 'alipay') {
                    $newItem = ['cash_type' => 'alipay', 'name' => '支付宝', 'icon' => IconHelper::alipay()];
                }
                if ($type == 'wechat') {
                    $newItem = ['cash_type' => 'wechat', 'name' => '微信零钱', 'icon' => IconHelper::wechat()];
                }
                if ($type == 'balance') {
                    $newItem = ['cash_type' => 'balance', 'name' => '余额', 'icon' => IconHelper::balance()];
                }
                $newCashType[] = $newItem;;
            }
            $setting['price']['cash_type'] = $newCashType;;
        }

        unset($newCashType);
        if ($setting['balance']['is_cash']) {
            $cash_type = $setting['balance']['cash_type'];
            $newCashType = [];
            foreach ($cash_type as $type) {
                $newItem = [];
                if ($type == 'bank') {
                    $newItem = ['cash_type' => 'bank', 'name' => '银行卡', 'icon' => IconHelper::bank()];
                }
                if ($type == 'alipay') {
                    $newItem = ['cash_type' => 'alipay', 'name' => '支付宝', 'icon' => IconHelper::alipay()];
                }
                if ($type == 'wechat') {
                    $newItem = ['cash_type' => 'wechat', 'name' => '微信零钱', 'icon' => IconHelper::wechat()];
                }
                $newCashType[] = $newItem;;
            }
            $setting['balance']['cash_type'] = $newCashType;;
        }

        $bank = Bank::findOne(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0]);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => $setting, 'info' => $info, 'bank' => $bank, 'bank_list' => BankHelper::getBankList()]);
    }
}
