<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 15:17
 */

namespace app\jobs\user;


use app\events\UserEvent;
use app\helpers\CacheHelper;
use app\jobs\Job;
use yii\queue\Queue;

class UserLoginJob extends Job
{

    public $user_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {

        $event = new UserEvent();
        $event->user_id = $this->user_id;
       // \Yii::$app->trigger(UserEvent::CHECK_UPGRADE, $event);
    }
}
