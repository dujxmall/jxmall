<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-12
 * Time: 12:02
 */

namespace app\jobs\market;

use app\jobs\Job;
use app\models\UserCoupon;
use yii\queue\Queue;

class UserCouponExpireJob extends Job
{
    public $user_coupon_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        $user_coupon = UserCoupon::findOne(['is_delete' => 0, 'status' => 0, 'id' => $this->user_coupon_id]);
        if ($user_coupon) {
            $user_coupon->status = 2;
            $user_coupon->save();
        }
    }
}