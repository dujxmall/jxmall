<?php
/**
 * Created by PhpStorm.
 * Author:ganxh
 * User: cp
 * Date: 2021/9/27
 * Time: 14:14
 */

namespace app\helpers;


use app\jobs\Job;
use app\models\Queue;

class JobHelper
{

    /**
     * Created by: ganxh
     * Date: 2021/9/27
     * Time: 14:15
     * Note:
     * @param Job $job
     * @param int $delay
     */
    public static function push(Job $job, $delay = 0)
    {
        if (!is_int($delay)) {
            $delay = 0;
        }
        $id= \Yii::$app->queue->delay($delay)->push($job);
        Queue::updateAll(['class' => get_class($job)], ['id' => $id]);
        return $id;
    }
}
