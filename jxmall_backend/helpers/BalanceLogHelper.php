<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-17
 * Time: 17:27
 */

namespace app\helpers;


use app\models\BalanceLog;
use app\models\User;
use yii\base\Exception;

class BalanceLogHelper
{

    //增加
    public static function add($user_id, $money, $content = null, $from_user_id = 0, $to_user_id = 0)
    {
        $user = User::findOne($user_id);
        if ($user) {
            $user->money += $money;
            $user->total_money += $money;
            if ($user->save()) {
                $log = new BalanceLog();
                $log->mall_id = $user->mall_id;
                $log->user_id = $user_id;
                $log->money = $money;
                $log->type = 1;
                $log->content = $content;
                $log->current = $user->money;
                $log->from_user_id = $from_user_id;
                $log->to_user_id = $to_user_id;
                if ($log->save()) {
                    return true;
                }
            }
        }
        return false;

    }

    //减少
    public static function sub($user_id, $money, $content = null, $from_user_id = 0, $to_user_id = 0)
    {

        if($money<0){
            throw new Exception('金额必须大于0！');

        }
        $user = User::findOne($user_id);
        if ($user) {
            if ($user->money >= $money) {
                $user->money -= $money;
                //  $user->total_money = $money;
                if ($user->save()) {
                    $log = new BalanceLog();
                    $log->mall_id = $user->mall_id;
                    $log->user_id = $user_id;
                    $log->money = $money;
                    $log->content = $content;
                    $log->current = $user->money;
                    $log->from_user_id = $from_user_id;
                    $log->to_user_id = $to_user_id;
                    if (!$log->save()) {
                        throw new Exception($log->getErrors());
                    }
                } else {
                    throw new Exception($user->getErrors());
                }
            } else {
                throw new Exception('余额不足！');
                //    return false;
            }
        }
        return true;
    }

}
