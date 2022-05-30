<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-12
 * Time: 12:02
 */

namespace app\jobs;


use app\models\Coupon;
use app\models\UserCoupon;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class CouponExpireJob extends Job
{
    public $coupon_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $coupon = Coupon::findOne(['is_delete' => 0, 'is_expire' => 0, 'id' => $this->coupon_id]);
        if ($coupon) {
            $coupon->is_expire = 1;
            $coupon->save();
        }
    }
}