<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 15:28
 */

namespace app\jobs\user;


use app\helpers\OptionHelper;
use app\jobs\Job;
use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\models\Level;
use app\models\Order;
use app\models\Plugin;
use app\models\User;
use app\models\UserUpgradeLog;
use app\plugins\fdd\models\ContractTransaction;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class UserLevelUpgradeJob extends Job
{

    public $user_id;

    private $is_manual = false;

    private $level_at = null;
    /**
     * @var User $user
     */
    public $user;

    /**
     * @var Level $level
     */
    public $level;
    private $marks = '';
    private $type = 0;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $user = User::getUser($this->user_id);
        if (!$user) {
            return;
        }
        $this->user = $user;
        $list = Level::find()->where(['is_delete' => 0, 'mall_id' => $user->mall_id, 'enabled' => 1, 'is_auto' => 1])->andWhere(['>', 'level', $user->level])->all();
        if ($user->level_at) {
            if ($user->is_manual) {
                $this->is_manual = true;
                $this->level_at = $user->level_at;
            }
        }
        /**
         * @var Level[] $list
         *
         */
        foreach ($list as $item) {
            if ($item->is_contract_update) {
                try {
                    $exist = Plugin::findOne(['name' => 'fdd', 'is_delete' => 0]);
                    if (!$exist) {
                        \Yii::warning("未启用电子合同，用户升级{$item->name}失败,用户ID：" . $user->id);
                        continue;
                    }
                    $exist = ContractTransaction::find()->andWhere(['user_id' => $user->id, 'is_delete' => 0, 'status' => 1, 'tpl_id' => $item->contract_tpl_id])->exists();
                    if (!$exist) {
                        \Yii::warning("用户未签合同，用户升级{$item->name}失败,用户ID：" . $user->id);
                        continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            $upgrade = OptionHelper::get('level_upgrade_' . $item->id, $user->mall_id);
            if ($upgrade) {
                if ($upgrade && $item->is_auto == 1) {
                    //条件
                    $this->level = $item;
                    $res = $this->oneCondition($upgrade);
                    if ($res) { //可以升级
                        \Yii::warning("用户{$user->id}：由{$user->level_id}升至{$item->id}");
                        $log = new UserUpgradeLog();
                        $log->user_id = $this->user_id;
                        $log->before_level_id = $user->level_id;
                        $log->reason_by = $this->type;
                        $log->marks = $this->marks;
                        $user->level = $item->level;
                        $user->level_id = $item->id;
                        $user->is_manual = 0;
                        $user->level_at = time();
                        $user->save();
                        try {
                            $log->mall_id = $user->mall_id;
                            $log->after_level_id = $user->level_id;
                            if (!$log->save()) {
                                \Yii::warning($log->getFirstErrors());
                            }
                        } catch (\Exception $exception) {
                            \Yii::warning($exception->getMessage());
                        }

                        return;
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
     * @param $list
     */
    private function oneCondition($list)
    {
        foreach ($list as $item) {
            if ($item['selected'] == 1) {
                $res = $this->checkCondition($item);
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
     * @param $condition
     */
    private function checkCondition($condition)
    {
        $type = $condition['type'];
        $is_ok = false;
        switch ($type) {
            case 0: //消费金额满
                if ($this->level->buy_type == 1) {
                    $query = CommonOrder::find()->alias('c')
                        ->andWhere(['c.status' => 1, 'c.user_id' => $this->user_id, 'c.is_delete' => 0]);
                } else {
                    $query = CommonOrder::find()->alias('c')
                        ->andWhere(['c.user_id' => $this->user_id, 'c.is_delete' => 0]);
                    $query->andWhere(['c.is_pay' => 1]);
                    $query->andWhere(['c.status' => [0, 1]]);
                }

                if ($this->level_at && $this->is_manual) {
                    $query->andWhere(['>', 'c.created_at', $this->user->level_at]);
                }

                $total_price = $query->sum('c.pay_price');
                if ($total_price >= $condition['value']) {
                    $is_ok = true;
                }
                $this->marks = '消费满：' . $condition['value'];
                $this->type = 0;
                break;
            case 1: //按商品
                $this->type = 1;
                $goods_ids = [];
                foreach ($condition['value'] as $item) {
                    $goods_ids[] = $item['id'];
                }
                if (count($goods_ids)) {
                    if ($this->level->buy_type) {
                        $query = CommonOrder::find()
                            ->alias('o')
                            ->innerJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                            ->andWhere(['o.user_id' => $this->user_id, 'od.goods_id' => $goods_ids])
                            ->andWhere(['o.status' => 1]);
                    } else {
                        $query = CommonOrder::find()
                            ->alias('o')
                            ->innerJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                            ->andWhere(['o.user_id' => $this->user_id, 'od.goods_id' => $goods_ids]);
                        $query->andWhere(['o.is_pay' => 1]);
                        $query->andWhere(['o.status' => [0, 1]]);
                    }

                    if ($this->level_at && $this->is_manual) {
                        $query->andWhere(['>', 'o.created_at', $this->user->level_at]);
                    }
                    $exist = $query->exists();
                    if ($exist) {
                        $is_ok = true;
                    }
                    $this->marks = '购买指定商品升级';
                }
                break;
            case 2:
                $this->type = 2;
                if ($this->user->level_id == $condition['value']) {
                    $goods_ids = $condition['value1'];
                    if ($this->level->buy_type) {
                        $query = CommonOrder::find()
                            ->alias('o')
                            ->innerJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                            ->andWhere(['o.user_id' => $this->user_id, 'od.goods_id' => $goods_ids])
                            ->andWhere(['o.status' => 1]);
                    } else {
                        $query = CommonOrder::find()
                            ->alias('o')
                            ->innerJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                            ->andWhere(['o.user_id' => $this->user_id, 'od.goods_id' => $goods_ids]);
                        $query->andWhere(['o.is_pay' => 1]);
                        $query->andWhere(['o.status' => [0, 1]]);
                    }
                    if ($this->level_at && $this->is_manual) {
                        $query->andWhere(['>', 'o.created_at', $this->user->level_at]);
                    }
                    $exist = $query->exists();
                    if ($exist) {
                        $is_ok = true;
                    }
                    $this->marks = '自己是某个等级且购买商品指定商品升级';
                }
                break;
            default:
                $is_ok = false;
                break;


        }
        return $is_ok;

    }

    private function allCondition($list)
    {
        foreach ($list as $item) {
            if ($item['selected'] == 1) {
                $res = $this->checkCondition($item);
                if (!$res) {
                    return false;
                }
            }
        }
        return true;
    }
}
