<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-25
 * Time: 9:16
 */

namespace app\jobs\user;


use app\helpers\OptionHelper;

use app\jobs\Job;
use app\models\CommonOrderDetail;
use app\models\Level;
use app\models\Order;
use app\models\User;

use yii\queue\Queue;

class CheckInviterJob extends Job
{

    public $user_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {

        // TODO: Implement execute() method.
        $user = User::findOne(['id' => $this->user_id, 'is_delete' => 0, 'is_inviter' => 0]);
        if (!$user) {
            return;
        }
        $level = Level::getOne($user->level_id);
        if ($level && $level->is_inviter) {
            $user->is_inviter = 1;
            $user->inviter_at = time();
            $user->save();
            return;
        }

        $setting = OptionHelper::get('relation_setting', $user->mall_id);
        if (!$setting) {
            return;
        }
        //无条件成为推广者
        if ($setting['invite_type'] == 0) {
            $user->is_inviter = 1;
            $user->inviter_at = time();
            $user->save();
            return;

        }

        //消费满
        if ($setting['invite_type'] == 3) {

            $buy_type = $setting['buy_type'];  //0 pay 1complete
            $query = Order::find()->where(['is_delete' => 0, 'user_id' => $this->user_id]);
            if ($buy_type == 0) {
                $sum_price = $query->andWhere(['or', ['is_pay' => 1], ['status' => 4]])->sum('pay_price');
            } else {
                $sum_price = $query->andWhere(['status' => 4])->sum('pay_price');
            }
            if ($sum_price && $sum_price >= floatval($setting['price'])) {
                $user->is_inviter = 1;
                $user->inviter_at = time();
                $user->save();
                return;
            }
        }


        //购物
        if ($setting['invite_type'] == 1) {
            if (count($setting['goods_ids'])) {
                if ($setting['buy_type'] == 0) {
                    $exist = Order::find()
                        ->alias('o')
                        ->where(['o.user_id' => $user->id, 'o.is_pay' => 1])
                        ->leftJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                        ->andWhere(['od.goods_id' => $setting['goods_ids']])
                        ->exists();
                    if ($exist) {
                        $user->is_inviter = 1;
                        $user->inviter_at = time();
                        $user->save();

                    }
                }
                if ($setting['buy_type'] == 1) {
                    $exist = Order::find()
                        ->alias('o')
                        ->where(['o.user_id' => $user->id, 'o.status' => Order::STATUS_IS_COMPLETE])
                        ->leftJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')
                        ->andWhere(['od.goods_id' => $setting['goods_ids']])
                        ->exists();
                    if ($exist) {
                        $user->is_inviter = 1;
                        $user->inviter_at = time();
                        $user->save();
                    }
                }
            }
        }
    }
}
