<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-09-07
 * Time: 23:22
 */

namespace app\jobs\user;


use app\jobs\Job;
use app\models\UserActionLog;
use yii\helpers\Json;
use yii\queue\Queue;

class UserActionJob extends Job
{


    public $user_id;
    public $mall_id;
    public $insert;
    public $changedAttributes;
    public $attributes;


    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        $insert = $this->insert;
        $changedAttributes = $this->changedAttributes;
        $arr = ['created_at', 'updated_at', 'deleted_at'];
        $afterUpdate = $this->attributes;
        $newBeforeUpdate = [];
        $newAfterUpdate = [];
        if ($insert) {
            $remark = '数据插入';
        } else {
            $remark = '数据更新';
        }
        if (isset($afterUpdate['is_delete']) && $afterUpdate['is_delete'] == 1) {
            $remark = '数据删除';
        }
        foreach ($changedAttributes as $key => $item) {
            if (in_array($key, $arr)) {
                unset($changedAttributes[$key]);
                continue;
            }
            if ($item != $afterUpdate[$key]) {
                try {
                    $newBeforeUpdate[$key] = Json::decode($item);
                } catch (\Exception $e) {
                    $newBeforeUpdate[$key] = $item;
                }

                try {
                    $newAfterUpdate[$key] = Json::decode($afterUpdate[$key]);
                } catch (\Exception $e) {
                    $newAfterUpdate[$key] = $afterUpdate[$key];
                }
            }
        }

        // TODO: Implement execute() method.

        $log = new \app\mongo\JxmallUserActionLog();

        $log->user_id = $this->user_id;
        $log->mall_id = $this->mall_id;
        $log->before_update = $newBeforeUpdate;
        $log->after_update = $newAfterUpdate;
        $log->remarks = $remark;
        if (!$log->save()) {
            \Yii::warning($log->getFirstErrors());
        }
    }
}
