<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-31
 * Time: 18:45
 */

namespace app\plugins\share\jobs;


use app\helpers\PriceLogHelper;
use app\helpers\SerializeHelper;
use app\plugins\share\models\Share;
use app\plugins\share\models\SharePriceLog;
use tests\app\RetryJob;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class ShareOrderStatusJob extends \app\jobs\Job
{

    public $status;
    public $common_order_id;
    public $common_order_detail_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        if ($this->status == 1) {//把钱发出去
            $list = SharePriceLog::find()->where(['common_order_detail_id' => $this->common_order_detail_id, 'status' => 0, 'is_delete' => 0])->all();
            /**
             * @var SharePriceLog $log
             */
            $t = \Yii::$app->db->beginTransaction();
            \Yii::warning('分钱队列');
            $flag = true;
            foreach ($list as $log) {
                $share = Share::findOne(['user_id' => $log->user_id, 'is_delete' => 0]);
                if ($share) {
                    $log->status = 1;
                    if (!$log->save()) {
                        \Yii::warning(SerializeHelper::encode($log->getErrors()));
                        $flag = false;
                        break;
                    }
                    $res = PriceLogHelper::savePrice($log->user_id, $log->price, '分销佣金到账',$this->common_order_detail_id);
                    if ($res) {
                        $share->total_price += $log->price;
                        if (!$share->save()) {
                            $flag = false;
                            break;
                        }
                    } else {
                        $flag = false;
                    }
                }
            }
            if ($flag) {
                $t->commit();
            } else {
                $t->rollBack();
            }
        }

        if ($this->status == 2) {
            \Yii::warning(SharePriceLog::updateAll(['status' => 2], ['common_order_detail_id' => $this->common_order_detail_id]));
        }
    }
}