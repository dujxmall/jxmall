<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 15:16
 */

namespace app\listeners;


use app\events\UserEvent;
use app\helpers\JobHelper;
use app\jobs\user\UserLevelUpgradeJob;
use app\jobs\user\UserLoginJob;

class UserListener extends BaseListener
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-22
     * @Time: 14:15
     * @Note:所有继承该类的都要实现事件注册的方法
     * @return mixed
     */
    function register()
    {
        // TODO: Implement register() method.
        \Yii::$app->on(UserEvent::LOGIN, function ($event) {
            JobHelper::push(new UserLoginJob(['user_id' => $event->user_id]), 3);
        });
        \Yii::$app->on(UserEvent::CHECK_UPGRADE, function ($event) {
            JobHelper::push(new UserLevelUpgradeJob(['user_id' => $event->user_id]), 3);
        });
    }
}