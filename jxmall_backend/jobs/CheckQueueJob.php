<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-06-11
 * Time: 8:17
 */

namespace app\jobs;


use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class CheckQueueJob extends Job
{

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.

        \Yii::warning('检测队列任务!');

    }
}