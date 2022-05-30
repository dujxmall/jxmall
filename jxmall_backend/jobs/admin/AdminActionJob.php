<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-09-07
 * Time: 23:22
 */

namespace app\jobs\admin;


use app\models\AdminActionLog;

use yii\queue\Queue;

class AdminActionJob extends \app\jobs\Job
{

    public $newBeforeUpdate;
    public $newAfterUpdate;
    public $model;
    public $model_id;
    public $remark;
    public $mall_id;
    public $admin_id;


    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.

        try {
            $log = new \app\mongo\JxmallAdminActionLog();
            $log->admin_id = $this->admin_id;
            $log->model = $this->model;
            $log->model_id = $this->model_id;
            $log->mall_id = $this->mall_id;
            $log->before_update = $this->newBeforeUpdate;
            $log->after_update = $this->newAfterUpdate;
            $log->remarks = $this->remark;
            if (!$log->save()) {
                \Yii::warning($log->getFirstErrors());
            }
        } catch (\Exception $e) {
            \Yii::warning($e->getMessage());
        }
    }
}
