<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/5
 * Time: 15:00
 * Note:
 */

namespace app\plugins\integral\listeners;


use app\events\UserEvent;
use app\helpers\JobHelper;
use app\listeners\BaseListener;
use app\plugins\integral\jobs\ParentChangeJob;

class ParentChangeListener extends BaseListener
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
        \Yii::$app->on(UserEvent::PARENT_CHANGE, function ($e) {
            $user_id = $e->user_id;
            JobHelper::push(new ParentChangeJob(['user_id'=>$user_id]),5);
        });
    }
}