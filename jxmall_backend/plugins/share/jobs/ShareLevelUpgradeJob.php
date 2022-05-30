<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 15:32
 */

namespace app\plugins\share\jobs;


use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\models\Order;
use app\models\User;
use app\models\UserParent;
use app\plugins\share\models\Share;
use app\plugins\share\models\ShareLevel;
use app\plugins\share\models\ShareOrder;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class ShareLevelUpgradeJob extends \app\jobs\Job
{
    public $user_id;
    public $setting;
    public $level_at = null;
    private $is_manual = false;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.


        \Yii::warning('分销检测队列');
        $user = User::findOne($this->user_id);
        if (!$user) {
            return;
        }
        $this->setting = OptionHelper::get('share_setting', $user->mall_id);
        if (!$this->setting) {
            return;
        }
        if (!$this->setting['level'] && $this->setting['level'] == 0) {
            return;
        }
        $share = Share::findOne(['user_id' => $this->user_id, 'is_delete' => 0]);
        if (!$share) {
            if ($user->is_inviter) {
                if ($this->setting['type'] == 0) {
                    $share = new Share();
                    $share->mall_id = $user->mall_id;
                    $share->user_id = $user->id;
                    $share->level = 0;
                    $share->is_auto = 1;
                    $share->total_price = 0;
                    $level = ShareLevel::findOne(['mall_id' => $user->mall_id, 'is_delete' => 0, 'level' => 0]);
                    if (!$level) {
                        return;
                    }
                    $share->level_id = $level->id;
                    if (!$share->save()) {
                        \Yii::warning($share->getFirstErrors());
                    }
                } elseif ($this->setting['type'] == 2) {
                    $gids = [];
                    foreach ($this->setting['list'] as $item) {
                        $gids[] = $item['id'];
                    }
                    $exist = CommonOrderDetail::find()->alias('d')
                        ->innerJoin(['c' => CommonOrder::tableName()], 'c.order_no=d.common_order_no')
                        ->andWhere(['d.goods_id' => $gids])
                        ->andWhere(['c.user_id' => $this->user_id, 'c.status' => [0, 1]])
                        ->exists();

                    if ($exist) {
                        $share = new Share();
                        $share->mall_id = $user->mall_id;
                        $share->user_id = $user->id;
                        $share->level = 0;
                        $share->is_auto = 1;
                        $share->total_price = 0;
                        $level = ShareLevel::findOne(['mall_id' => $user->mall_id, 'is_delete' => 0, 'level' => 0]);
                        if (!$level) {
                            \Yii::warning('没有添加默认等级');
                            return;
                        }
                        $share->level_id = $level->id;
                        if (!$share->save()) {
                            \Yii::warning($share->getFirstErrors());
                        }
                    } else {
                        return;
                    }

                } elseif ($this->setting['type'] == 3) {
                    $sumPrice = CommonOrder::find()->andWhere(['user_id' => $this->user_id, 'is_delete' => 0])->andWhere(['status' => [0, 1]])->sum('pay_price');
                    if ($sumPrice >= $this->setting['price']) {
                        $share = new Share();
                        $share->mall_id = $user->mall_id;
                        $share->user_id = $user->id;
                        $share->level = 0;
                        $share->is_auto = 1;
                        $share->total_price = 0;
                        $level = ShareLevel::findOne(['mall_id' => $user->mall_id, 'is_delete' => 0, 'level' => 0]);
                        if (!$level) {
                            return;
                        }
                        $share->level_id = $level->id;
                        if (!$share->save()) {
                            \Yii::warning($share->getFirstErrors());
                        }
                    } else {
                        \Yii::warning('消费金额不足');
                        return;
                    }
                } else {
                    return;
                }
            }
        }
        if (!$share) {
            return;
        }
        if (!$share->is_auto) {
            return;
        }
        if ($share->level_at) {
            if ($share->is_manual) {
                $this->level_at = $share->level_at;
                $this->is_manual = true;
            }
        }

        $list = ShareLevel::find()->where(['mall_id' => $share->mall_id, 'is_delete' => 0])->andWhere(['>', 'level', $share->level])->all();
        /**
         * @var ShareLevel $item
         */
        foreach ($list as $item) {
            $level = OptionHelper::get('share_level_' . $item->id, $share->mall_id);
            if ($level && $level['upgrade']) {
                $upgrade = SerializeHelper::decode($level['upgrade']);
                if ($upgrade && $upgrade['is_auto'] == 1) {

                    //条件
                    if ($upgrade['type'] == 0) { //满足任意
                        $res = $this->oneCondition($share, $upgrade['list']);
                        if ($res) { //可以升级
                            $share->level = $item->level;
                            $share->level_id = $item->id;
                            $share->is_manual = 0;
                            $share->level_at = time();
                            $share->save();
                            return;
                        }
                    }
                    if ($upgrade['type'] == 1) {
                        $res = $this->allCondition($share, $upgrade['list']);
                        if ($res) { //可以升级
                            $share->level = $item->level;
                            $share->level_id = $item->id;
                            $share->is_manual = 0;
                            $share->level_at = time();
                            $share->save();
                            return;
                        }
                    }
                }
            }

        }
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 16:00
     * @Note:
     * @param Share $share
     * @param $list
     */
    private function oneCondition($share, $list)
    {
        foreach ($list as $item) {

            if ($item['selected'] == 1) {

                $res = $this->checkCondition($share, $item);
                if ($res) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-30
     * @Time: 16:02
     * @Note:
     * @param Share $share
     * @param $condition
     */
    private function checkCondition($share, $condition)
    {
        $type = $condition['type'];
        $is_ok = false;
        switch ($type) {
            case 0:
                $query = ShareOrder::find()->alias('s')
                    ->leftJoin(['c' => CommonOrder::tableName()], 'c.id=s.common_order_id')
                    ->andWhere(['c.status' => 1, 's.is_delete' => 0, 'c.is_delete' => 0]);
                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'c.created_at', $this->level_at]);
                }
                if ($this->setting['level'] == 1) {
                    $query->andWhere(['s.parent_id_1' => $share->user_id]);
                }
                if ($this->setting['level'] == 2) {
                    $query->andWhere(['or', ['s.parent_id_1' => $share->user_id], ['s.parent_id_2' => $share->user_id]]);
                }
                if ($this->setting['level'] == 3) {
                    $query->andWhere(['or', ['s.parent_id_1' => $share->user_id], ['s.parent_id_2' => $share->user_id], ['s.parent_id_3' => $share->user_id]]);
                }
                $total_price = $query->sum('c.total_price');
                if ($total_price >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 1:
                $query = ShareOrder::find()->alias('s')
                    ->leftJoin(['c' => CommonOrder::tableName()], 'c.id=s.common_order_id')
                    ->andWhere(['c.status' => 1, 's.is_delete' => 0, 'c.is_delete' => 0]);
                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'c.created_at', $this->level_at]);
                }
                if ($this->setting['level'] == 1) {
                    $query->andWhere(['s.parent_id_1' => $share->user_id]);
                }
                if ($this->setting['level'] == 2) {
                    $query->andWhere(['or', ['s.parent_id_1' => $share->user_id], ['s.parent_id_2' => $share->user_id]]);
                }
                if ($this->setting['level'] == 3) {
                    $query->andWhere(['or', ['s.parent_id_1' => $share->user_id], ['s.parent_id_2' => $share->user_id], ['s.parent_id_3' => $share->user_id]]);
                }
                $total = $query->select('c.id')->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 2:
                $query = ShareOrder::find()->alias('s')
                    ->leftJoin(['c' => CommonOrder::tableName()], 'c.id=s.common_order_id')
                    ->andWhere(['c.status' => 1, 's.is_delete' => 0, 'c.is_delete' => 0]);
                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'c.created_at', $this->level_at]);
                }
                $query->andWhere(['s.parent_id_1' => $share->user_id]);
                $total_price = $query->sum('c.total_price');
                if ($total_price >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 3:
                $query = ShareOrder::find()->alias('s')
                    ->leftJoin(['c' => CommonOrder::tableName()], 'c.id=s.common_order_id')
                    ->andWhere(['c.status' => 1, 's.is_delete' => 0, 'c.is_delete' => 0]);
                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'c.created_at', $this->level_at]);
                }
                $query->andWhere(['s.parent_id_1' => $share->user_id]);
                $total = $query->select('c.id')->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 4:
                $query = CommonOrder::find()
                    ->andWhere(['status' => 1, 'is_delete' => 0]);
                $query->andWhere(['user_id' => $share->user_id]);
                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'created_at', $this->level_at]);
                }
                $total_price = $query->sum('total_price');
                if ($total_price >= $condition['value']) {
                    $is_ok = true;
                }

                break;
            case 5:
                $query = CommonOrder::find()
                    ->andWhere(['status' => 1, 'is_delete' => 0]);
                $query->andWhere(['user_id' => $share->user_id]);
                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'o.created_at', $this->level_at]);
                }
                $total = $query->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 6:
                //下级总人数
                $query = UserParent::find()
                    ->andWhere(['parent_id' => $share->user_id, 'is_delete' => 0]);
                $total = $query->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 7:
                //下级分销总人数
                $query = UserParent::find()->alias('u')
                    ->leftJoin(['s' => Share::tableName()], 's.user_id=u.user_id')
                    ->andWhere(['u.parent_id' => $share->user_id, 'u.is_delete' => 0, 's.is_delete' => 0]);
                $total = $query->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 8:
                //下级总人数
                $query = UserParent::find()
                    ->andWhere(['parent_id' => $share->user_id, 'is_delete' => 0, 'level' => 1]);
                $total = $query->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 9:
                //下级分销总人数
                $query = UserParent::find()->alias('u')
                    ->leftJoin(['s' => Share::tableName()], 's.user_id=u.user_id')
                    ->andWhere(['u.parent_id' => $share->user_id, 'u.is_delete' => 0, 's.is_delete' => 0, 'u.level' => 1]);
                $total = $query->count();
                if ($total >= $condition['value']) {
                    $is_ok = true;
                }
                break;
            case 10:
                $goods_ids = [];
                foreach ($condition['value'] as $item) {
                    $goods_ids[] = $item['id'];
                }
                if (count($goods_ids)) {
                    $query = Order::find()->alias('o')->leftJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                        ->andWhere(['o.user_id' => $share->user_id, 'od.goods_id' => $goods_ids])
                        ->andWhere(['o.status' => Order::STATUS_IS_COMPLETE]);
                    if ($this->level_at && $this->is_manual) {
                        $query->andWhere(['>', 'o.created_at', $this->level_at]);
                    }
                    $exist = $query->exists();
                    if ($exist) {
                        $is_ok = true;
                    }
                }
                break;
            case 11:
                break;
            default:
                $is_ok = false;
                break;


        }
        return $is_ok;

    }

    private function allCondition($share, $list)
    {
        foreach ($list as $item) {

            if ($item['selected'] == 1) {

                $res = $this->checkCondition($share, $item);
                if (!$res) {
                    return false;
                }
            }
        }
        return true;
    }
}
