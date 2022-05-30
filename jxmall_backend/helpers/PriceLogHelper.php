<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-31
 * Time: 22:31
 */

namespace app\helpers;


use app\models\CommonOrderDetail;
use app\models\PriceLog;
use app\models\User;

/**
 * Class PriceLogHelper
 * @package app\helpers
 * @Notes 佣金助手
 */
class PriceLogHelper
{

    public static function savePrice($user_id, $price, $remarks,$common_order_detail_id=0)
    {
        $user = User::findOne(['is_delete' => 0, 'id' => $user_id]);
        if (!$user) {
            return false;
        }
        $user->total_price += $price;
        $user->price += $price;
        if ($user->save()) {
            $log = new PriceLog();
            $log->mall_id = $user->mall_id;
            $log->user_id = $user_id;
            $log->price = $price;
            $log->remarks = $remarks;
            $log->common_order_detail_id=$common_order_detail_id;
            $order = CommonOrderDetail::findOne($common_order_detail_id);
            if ($order) {
                $log->buy_user_id = $order->user_id;
                $log->order_no = $order->common_order_no;
            }
            if ($log->save()) {
                return true;
            }else{
                \Yii::warning(SerializeHelper::encode($log->getErrors()));
            }
        }else{
            \Yii::warning(SerializeHelper::encode($user->getErrors()));
        }
        return false;
    }
}
