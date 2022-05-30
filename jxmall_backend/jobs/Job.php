<?php
/**
 * Created by PhpStorm.
 * Author:ganxh
 * User: cp
 * Date: 2021/9/27
 * Time: 14:12
 */

namespace app\jobs;


use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class Job extends BaseObject implements JobInterface
{

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
    }
}
