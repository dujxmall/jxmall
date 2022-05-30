<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-24
 * Time: 16:21
 */

namespace app\jobs\user;


use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\jobs\Job;
use app\models\CommonOrder;
use app\models\User;
use app\models\UserParent;
use app\mongo\JxmallUserParent;
use yii\queue\Queue;

class BindParentJob extends Job
{

    public $user_id;
    public $parent_id;

    /**
     * @var User $user
     */
    public $user;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {

        \Yii::warning('绑定上级的队列');
        // TODO: Implement execute() method.
        $user = User::findOne(['id' => $this->user_id, 'parent_id' => 0]);
        if ($this->parent_id == -1 && $user->maybe_parent_id == 0) {
            return;
        }
        if ($user->is_inviter) {
            return;
        }

        $this->user = $user;
        $setting = OptionHelper::get('relation_setting', $user->mall_id);
        if ($user->maybe_parent_id && ($this->parent_id == -1 || !$this->parent_id)) {
            if ($setting['child_type'] == 1) {//首次下单
                $common_order = CommonOrder::find()->where(['user_id' => $this->user_id, 'is_delete' => 0, 'status' => [1, 0]])->exists();
                if (!$common_order) {
                    return;
                }
            }
            if ($setting['child_type'] == 2) {//首次下单
                $common_order = CommonOrder::find()->where(['user_id' => $this->user_id, 'is_delete' => 0, 'is_pay' => 1])->exists();
                if (!$common_order) {
                    return;
                }
            }
            if ($setting['child_type'] != 0) {
                return;
            }
            $user->parent_id = $user->maybe_parent_id;
            $user->parent_at = time();
            if ($user->save()) {
                $this->createRelation();
            }
            return;
        }

        if ($user->id == $this->parent_id) {
            return;
        }
        if ($user->parent_id == $this->parent_id) {
            return;
        }
        $parent = User::findOne(['id' => $this->parent_id, 'mall_id' => $user->mall_id, 'is_inviter' => 1]);
        if (!$parent) {
            return;
        }
        $exist = UserParent::find()->where(['user_id' => $this->parent_id, 'parent_id' => $this->user_id])->exists();
        if ($exist) {
            return;
        }
        //判断成为下级的条件 如果是需要下单或者需要付款  那么这里应该是 maybe_parent_id
        if ($setting['child_type'] != 0) { //不是直接绑定
            if ($user->maybe_parent_id != $this->parent_id) {
                $user->maybe_parent_id = $this->parent_id;
                $user->save();
                if ($setting['child_type'] == 1) {//首次下单
                    \Yii::warning('首次下单');
                    $common_order = CommonOrder::find()->where(['user_id' => $this->user_id, 'is_delete' => 0, 'status' => [1, 0]])->exists();
                    if (!$common_order) {
                        \Yii::warning('没有下订单');
                        return;
                    }
                }
                if ($setting['child_type'] == 2) {//首次付款
                    \Yii::warning('首次付款');
                    $common_order = CommonOrder::find()->where(['user_id' => $this->user_id, 'is_delete' => 0, 'is_pay' => 1])->exists();
                    if (!$common_order) {
                        \Yii::warning('没有付款订单');
                        return;
                    }
                }
            } else {
                if ($setting['child_type'] == 1) {//首次下单
                    \Yii::warning('首次下单');
                    $common_order = CommonOrder::find()->where(['user_id' => $this->user_id, 'is_delete' => 0, 'status' => [1, 0]])->exists();
                    if (!$common_order) {
                        \Yii::warning('没有下订单');
                        return;
                    }
                }
                if ($setting['child_type'] == 2) {//首次付款
                    \Yii::warning('首次付款');
                    $common_order = CommonOrder::find()->where(['user_id' => $this->user_id, 'is_delete' => 0, 'is_pay' => 1])->exists();
                    if (!$common_order) {
                        \Yii::warning('没有付款订单');
                        return;
                    }
                }
            }
        }
        $user->parent_id = $this->parent_id;
        $user->parent_at = time();
        if ($user->save()) {
            $this->createRelation();
        }

    }

    private function createRelation()
    {
        // TODO: Implement execute() method.
        $user = User::findOne($this->user_id);
        if (!$user) {
            return;
        }
        $user_list = UserParent::find()->where(['parent_id' => $this->user_id])->orderBy('level asc')->select('user_id')->distinct()->column();
        if (!$user_list) {
            $user_list = [];
        }
        array_unshift($user_list, $this->user_id);//将当前的用户ID 插入到最前面
        UserParent::deleteAll(['user_id' => $user_list]);//  删除相关的
        if ($this->parent_id) {
            array_unshift($user_list, $this->parent_id);//将当前的父级ID 插入到最前面
        }
        for ($i = 1; $i < count($user_list); $i++) {
            $this->doRelation($user_list[$i], $user_list[$i - 1]);
        }
    }

    public function doRelation($user_id, $parent_id)
    {
        $parent = UserParent::findOne(['user_id' => $user_id, 'parent_id' => $parent_id, 'level' => 1]);
        if (!$parent) {
            $parent = new UserParent();
            $parent->user_id = $user_id;
            $parent->parent_id = $parent_id;
            $parent->level = 1;
            $parent->mall_id = 1;
            $parent->save();
        }
        $parent_list = UserParent::find()
            ->where(['user_id' => $parent_id, 'is_delete' => 0])
            ->orderBy('level asc')
            ->select('parent_id,user_id,level')
            ->distinct()
            ->all();
        /**
         * @var UserParent[] $parent_list
         */
        foreach ($parent_list as $item) {
            $parent = UserParent::findOne(['user_id' => $user_id, 'parent_id' => $item->parent_id, 'level' => 1 + $item->level]);
            if (!$parent) {
                $parent = new UserParent();
                $parent->user_id = $user_id;
                $parent->parent_id = $item->parent_id;
                $parent->level = 1 + $item->level;
                $parent->mall_id = 1;
                if (!$parent->save()) {
                    \Yii::warning(SerializeHelper::encode($parent));
                }
            }
        }
        unset($item);
    }

}
