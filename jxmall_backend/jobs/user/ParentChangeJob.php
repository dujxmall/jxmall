<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-24
 * Time: 17:20
 */

namespace app\jobs;


use app\models\User;
use app\models\UserParent;
use yii\base\BaseObject;
use yii\queue\Job;
use yii\queue\JobInterface;
use yii\queue\Queue;

class ParentChangeJob extends BaseObject implements JobInterface
{
    public $parent_id;
    public $user_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $user = User::findOne($this->user_id);
        if (!$user) {
            return;
        }
        $user_list = UserParent::find()
            ->where(['parent_id' => $this->user_id])
            ->orderBy('level asc')
            ->select('user_id')
            ->distinct()
            ->column();
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


        $parent = UserParent::findOne(['user_id' => $user_id, 'level' => 1, 'parent_id' => $parent_id]);
        if (!$parent) {
            $parent = new UserParent();
            $parent->user_id = $user_id;
            $parent->parent_id = $parent_id;
            $parent->level = 1;
            $parent->mall_id = 1;
            $parent->save();
        }

        $parent_list = UserParent::find()
            ->where(['user_id' => $parent_id,'is_delete'=>0])
            ->orderBy('level asc')
            ->select('parent_id,user_id,level')
            ->distinct()
            ->all();
        /**
         * @var UserParent[] $parent_list
         */
        foreach ($parent_list as $item) {
            $parent = UserParent::findOne(['user_id' => $user_id, 'level' => 1 + $item->level, 'parent_id' => $item->parent_id]);
            if (!$parent) {
                $parent = new UserParent();
                $parent->user_id = $user_id;
                $parent->parent_id = $item->parent_id;
                $parent->level = 1 + $item->level;
                $parent->mall_id = 1;
                $parent->save();
            }
        }
        unset($item);
    }
}
