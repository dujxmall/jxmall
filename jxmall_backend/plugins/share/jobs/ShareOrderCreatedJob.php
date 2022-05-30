<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-31
 * Time: 16:59
 */

namespace app\plugins\share\jobs;


use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\jobs\Job;
use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\models\UserParent;
use app\plugins\share\models\Share;
use app\plugins\share\models\ShareGoods;
use app\plugins\share\models\ShareOrder;
use app\plugins\share\models\SharePriceLog;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class ShareOrderCreatedJob extends Job
{
    public $common_order_id;
    public $total_share_price;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.

        $common_order = CommonOrder::findOne(['is_delete' => 0, 'id' => $this->common_order_id]);
        if (!$common_order) {
            return;
        }
        $share_setting = OptionHelper::get('share_setting', $common_order->mall_id);
        if (!$share_setting || empty($share_setting)) {
            return;
        }
        $share_level = $share_setting['level'];
        $self_buy = $share_setting['self_buy'];
        if (!$share_level) {
            return;
        }
        $user_parent_list = UserParent::find()->where(['user_id' => $common_order->user_id])->andWhere(['<=', 'level', 3])->orderBy('level asc')->select('parent_id')->distinct()->asArray()->all();
        if ($self_buy == 1) {
            if (!$user_parent_list || empty($user_parent_list)) {
                array_push($user_parent_list, ['parent_id' => $common_order->user_id]);
            } else {
                array_unshift($user_parent_list, ['parent_id' => $common_order->user_id]);
            }
        } else {
            if (!$user_parent_list || empty($user_parent_list)) {
                return;
            }
        }
        \Yii::warning(SerializeHelper::encode($user_parent_list));
        $order = new ShareOrder();
        $order->mall_id = $common_order->mall_id;
        $order->common_order_id = $this->common_order_id;
        $order->user_id = $common_order->user_id;
        $is_share_order = false;
        if (count($user_parent_list) > 0) {
            if ($share_level > 0) {
                $share = Share::findOne(['user_id' => $user_parent_list[0]['parent_id']]);
                if ($share) {
                    $is_share_order = true;
                    $order->parent_id_1 = $user_parent_list[0]['parent_id'];
                }
            }
        }
        if (count($user_parent_list) > 1) {
            if ($share_level > 1) {
                $share = Share::findOne(['user_id' => $user_parent_list[1]['parent_id']]);
                if ($share) {
                    $order->parent_id_2 = $user_parent_list[1]['parent_id'];
                }

            }
        }
        if (count($user_parent_list) > 2) {
            if ($share_level > 2) {
                $share = Share::findOne(['user_id' => $user_parent_list[2]['parent_id']]);
                if ($share) {
                    $order->parent_id_3 = $user_parent_list[2]['parent_id'];
                }
            }
        }
        if (!$is_share_order) {
            return;
        }
        if ($order->save()) {
            $this->savePriceLog($order);
        }
        $order->share_price = $this->total_share_price;
        $order->save();

    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-31
     * @Time: 17:34
     * @Note:
     * @param ShareOrder $share_order
     */

    private function savePriceLog($share_order)
    {
        $order_detail_list = CommonOrderDetail::find()->where(['common_order_id' => $this->common_order_id, 'is_delete' => 0])->all();
        /**
         * @var CommonOrderDetail[] $order_detail_list
         */
        foreach ($order_detail_list as $detail) {
            //商城商品
            $shareGoods = ShareGoods::findOne(['goods_id' => $detail->goods_id, 'is_share_goods' => 1]);
            if ($shareGoods) {
                if ($share_order->parent_id_1) {
                    $share = Share::findOne(['user_id' => $share_order->parent_id_1, 'is_delete' => 0]);
                    if ($share) {
                        $log = new SharePriceLog();
                        $log->goods_id = $detail->goods_id;
                        $log->goods_type = 0;
                        $log->common_order_id = $this->common_order_id;
                        $log->common_order_detail_id = $detail->id;
                        $log->user_id = $share->user_id;
                        $log->share_order_id = $share_order->id;
                        $log->mall_id = $share_order->mall_id;
                        $share_price = 0;
                        if ($shareGoods->is_alone) {//独立设置
                            \Yii::warning('独立设置');
                            $price_type = $shareGoods->price_type;
                            $price_list = OptionHelper::get('goods_price_list_' . $shareGoods->id);
                            if ($price_list) {
                                foreach ($price_list as $price) {
                                    if ($price['level'] == $share->level) {
                                        if ($price_type == 0) { //固定金额
                                            $share_price = $price['first_price'] * $detail->num;
                                        }
                                        if ($price_type == 1) {
                                            $share_price = ($price['first_price'] / 100) * $detail->price;
                                        }
                                    }
                                }
                            }
                        } else { //非独立设置
                            \Yii::warning('系统设置');
                            $share_level = OptionHelper::get('share_level_' . $share->level_id, $share->mall_id);
                            if ($share_level) {
                                $price_type = $share_level['price_type'];
                                if ($price_type == 1) {//百分比
                                    $share_price = ($share_level['first_price'] / 100) * $detail->price;
                                }
                                if ($price_type == 0) {//百分比
                                    $share_price = $share_level['first_price'] * $detail->num;
                                }
                            }
                        }
                        if ($share_price) {

                            $this->total_share_price += $share_price;
                            $log->price = $share_price;
                            $log->save();
                        }
                    }
                }
                if ($share_order->parent_id_2) {
                    $share = Share::findOne(['user_id' => $share_order->parent_id_2, 'is_delete' => 0]);
                    if ($share) {
                        $log = new SharePriceLog();
                        $log->goods_id = $detail->goods_id;
                        $log->goods_type = 0;
                        $log->common_order_id = $this->common_order_id;
                        $log->common_order_detail_id = $detail->id;
                        $log->user_id = $share->user_id;
                        $log->share_order_id = $share_order->id;
                        $log->mall_id = $share_order->mall_id;
                        $share_price = 0;
                        if ($shareGoods->is_alone) {//独立设置
                            \Yii::warning('独立设置');
                            $price_type = $shareGoods->price_type;
                            $price_list = OptionHelper::get('goods_price_list_' . $shareGoods->id);
                            if ($price_list) {
                                foreach ($price_list as $price) {
                                    if ($price['level'] == $share->level) {
                                        if ($price_type == 0) { //固定金额
                                            $share_price = $price['first_price'] * $detail->num;
                                        }
                                        if ($price_type == 1) {
                                            $share_price = ($price['first_price'] / 100) * $detail->price;
                                        }
                                    }
                                }
                            }
                        } else { //非独立设置
                            \Yii::warning('系统设置');
                            $share_level = OptionHelper::get('share_level_' . $share->level_id, $share->mall_id);
                            if ($share_level) {
                                $price_type = $share_level['price_type'];
                                if ($price_type == 1) {//百分比
                                    $share_price = ($share_level['second_price'] / 100) * $detail->price;
                                }
                                if ($price_type == 0) {//百分比
                                    $share_price = $share_level['second_price'] * $detail->num;
                                }
                            }
                        }
                        if ($share_price) {
                            $this->total_share_price += $share_price;
                            $log->price = $share_price;
                            $log->save();
                        }
                    }
                }
                if ($share_order->parent_id_3) {
                    $share = Share::findOne(['user_id' => $share_order->parent_id_3, 'is_delete' => 0]);
                    if ($share) {
                        $log = new SharePriceLog();
                        $log->goods_id = $detail->goods_id;
                        $log->goods_type = 0;
                        $log->common_order_id = $this->common_order_id;
                        $log->common_order_detail_id = $detail->id;
                        $log->user_id = $share->user_id;
                        $log->share_order_id = $share_order->id;
                        $log->mall_id = $share_order->mall_id;
                        $share_price = 0;
                        if ($shareGoods->is_alone) {//独立设置
                            \Yii::warning('独立设置');
                            $price_type = $shareGoods->price_type;
                            $price_list = OptionHelper::get('goods_price_list_' . $shareGoods->id);
                            if ($price_list) {
                                foreach ($price_list as $price) {
                                    if ($price['level'] == $share->level) {
                                        if ($price_type == 0) { //固定金额
                                            $share_price = $price['third_price'] * $detail->num;
                                        }
                                        if ($price_type == 1) {
                                            $share_price = ($price['third_price'] / 100) * $detail->price;
                                        }
                                    }
                                }
                            }
                        } else { //非独立设置
                            \Yii::warning('系统设置');
                            $share_level = OptionHelper::get('share_level_' . $share->level_id, $share->mall_id);
                            if ($share_level) {
                                $price_type = $share_level['price_type'];
                                if ($price_type == 1) {//百分比
                                    $share_price = ($share_level['third_price'] / 100) * $detail->price;
                                }
                                if ($price_type == 0) {//百分比
                                    $share_price = $share_level['third_price'] * $detail->num;
                                }
                            }
                        }
                        if ($share_price) {
                            $this->total_share_price += $share_price;
                            $log->price = $share_price;
                            $log->save();
                        }
                    }
                }
            }
        }
    }
}