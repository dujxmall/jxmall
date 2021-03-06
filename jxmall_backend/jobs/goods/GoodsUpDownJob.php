<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/2/23
 * Time: 10:03
 * Note:
 */

namespace app\jobs\goods;


use app\jobs\Job;
use app\models\Goods;


class GoodsUpDownJob extends Job
{
    public $job_id;
    public function execute($queue)
    {
        $job = \app\models\GoodsUpDownJob::getOne($this->job_id);
        if ($job) {
            $goods = Goods::findOne($job->goods_id);
            if ($goods) {
                if ($job->status == 1) {
                    \Yii::warning($goods->name . '执行下架');
                    $goods->status = 0;
                }
                if ($job->status == 2) {
                    \Yii::warning($goods->name . '执行上架');
                    $goods->status = 1;
                }
                $goods->save();
                $job->is_done = 1;
                $job->save();
            }
        }
        parent::execute($queue); // TODO: Change the autogenerated stub
    }

}