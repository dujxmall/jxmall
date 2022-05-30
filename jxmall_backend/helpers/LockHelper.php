<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/29
 * Time: 10:05
 * Note:
 */

namespace app\helpers;


class LockHelper
{
    public static function setUnionOrderPay($order_id)
    {
        if (\Yii::$app->redis->setnx('UNINON_ORDER_PAY_' . $order_id, 1)) {
            \Yii::$app->redis->expire('UNINON_ORDER_PAY_' . $order_id, 5 * 60);
            return 1;
        }
        return 0;
    }

    public static function setCommonOrderPay($order_id)
    {
        if (\Yii::$app->redis->setnx('COMMON_ORDER_PAY_' . $order_id, 1)) {
            \Yii::$app->redis->expire('COMMON_ORDER_PAY_' . $order_id, 5 * 60);
            return 1;
        }
        return 0;
    }

    public static function setUserLogin($user_id)
    {
        if (\Yii::$app->redis->setnx('USER_LOGIN_' . $user_id, 1)) {
            \Yii::$app->redis->expire('USER_LOGIN_' . $user_id, 5);
            return 1;
        }
        return 0;
    }

    public static function setWechatOrder($order_id)
    {
        if (\Yii::$app->redis->setnx('WECHAT_ORDER_' . $order_id, 1)) {
            \Yii::$app->redis->expire('WECHAT_ORDER_' . $order_id, 30);
            return 1;
        }
        return 0;
    }

    public static function setSendLevel($common_order_detail_id)
    {
        if (\Yii::$app->redis->setnx('SEND_LEVEL_' . $common_order_detail_id, 1)) {
            \Yii::$app->redis->expire('SEND_LEVEL_' . $common_order_detail_id, 30);
            return 1;
        }
        return 0;
    }

    public static function lock($key, $sec)
    {
        if (\Yii::$app->redis->setnx($key, $sec)) {
            if ($sec) {
                \Yii::$app->redis->expire($key, $sec);
            }
            return 1;
        }
        return 0;
    }

}