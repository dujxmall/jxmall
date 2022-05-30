<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/29
 * Time: 9:03
 * Note:
 */

namespace app\jobs\test;


use app\helpers\ConstantHelper;
use app\helpers\WechatHelper;
use app\jobs\Job;

class TestJob extends Job
{

    public function execute($queue)
    {
        \Yii::$app->platform = ConstantHelper::PLATFORM_MPWX;
        try {
            \Yii::$app->platform = ConstantHelper::PLATFORM_MPWX;
            WechatHelper::init(1);
            $app = \Yii::$app->wechat->payment;
            $res = $app->order->queryByOutTradeNumber('U2022042115422816505269484');
            \Yii::warning($res);
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
        //   parent::execute($queue); // TODO: Change the autogenerated stub
    }
}