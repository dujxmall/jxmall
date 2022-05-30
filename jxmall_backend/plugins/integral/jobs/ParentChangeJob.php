<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/5
 * Time: 15:02
 * Note:
 */

namespace app\plugins\integral\jobs;


use app\helpers\IntegralLogHelper;
use app\helpers\OptionHelper;
use app\jobs\Job;
use app\models\User;
use app\plugins\integral\models\InviteIntegralLog;

class ParentChangeJob extends Job
{
    public $user_id;

    public function execute($queue)
    {
        $user = User::findOne($this->user_id);
        //奖励积分
        if (!$user) {
            return;
        }
        if (!$user->parent_id) {
            return;
        }
        $log = User::find()->andWhere(['user_id' => $this->user_id, 'is_delete' => 0])->exists();
        if ($log) {
            return;
        }
        $setting = OptionHelper::get('plugin_integral_setting', $user->mall_id);
        if (!$setting || !$setting['is_invite_integral']) {
            return;
        }
        $integral = $setting['invite_integral'];
        if (!$integral || !is_numeric($integral)) {
            return;
        }
        $log = new InviteIntegralLog();
        $log->mall_id = $user->mall_id;
        $log->user_id = $user->id;
        $log->parent_user_id = $user->parent_id;
        $log->integral = $integral;
        if (!$log->save()) {
            \Yii::warning($log->getFirstErrors());
            return;
        }
        IntegralLogHelper::add($log->parent_user_id, $log->integral, '邀请用户：' . $user->nickname . ',获得积分');
    }

}