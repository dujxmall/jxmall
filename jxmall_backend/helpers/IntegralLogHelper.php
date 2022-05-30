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
use app\models\IntegralLog;
use app\models\User;
use yii\base\Exception;

class IntegralLogHelper
{


    //增加
    public static function add($user_id, $integral, $content = null)
    {

        $user = User::findOne($user_id);
        if ($user) {
            $user->integral += $integral;
            $user->total_integral += $integral;
            if ($user->save()) {
                $log = new IntegralLog();
                $log->mall_id = $user->mall_id;
                $log->user_id = $user_id;
                $log->integral = $integral;
                $log->type = 1;
                $log->content = $content;
                $log->current = $user->integral;
                if ($log->save()) {
                    return true;
                }
            }
        }
        return false;

    }

    //减少
    public static function sub($user_id, $integral, $content = null)
    {
        $user = User::findOne($user_id);
        if ($user) {
            if ($user->integral >= $integral) {
                $user->integral -= $integral;
                if ($user->save()) {
                    $log = new IntegralLog();
                    $log->mall_id = $user->mall_id;
                    $log->user_id = $user_id;
                    $log->integral = $integral;
                    $log->content = $content;
                    $log->type = 0;
                    $log->current = $user->integral;
                    if (!$log->save()) {
                        throw new Exception($log->getErrors());
                    }
                } else {
                    throw new Exception($user->getErrors());
                }
            } else {
                throw new Exception('积分不足！');
                //    return false;
            }
        }
        return true;
    }

}