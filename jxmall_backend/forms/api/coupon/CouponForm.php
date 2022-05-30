<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-12
 * Time: 0:05
 */

namespace app\forms\api\coupon;


use app\core\ApiCode;
use app\helpers\DateHelper;
use app\models\BaseModel;
use app\models\Coupon;
use app\models\GoodsCoupon;
use app\models\UserCoupon;

class CouponForm extends BaseModel
{
    public $page;
    public $limit;
    public $coupon_id;
    public $status;
    public $gid;

    public function rules()
    {
        return [
            [['coupon_id', 'page', 'limit', 'status', 'gid'], 'integer'],
            [['limit'], 'default', 'value' => 10]
        ]; // TODO: Change the autogenerated stub
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-12
     * @Time: 0:06
     * @Note:领取优惠券
     */
    public function receive()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $coupon = Coupon::findOne(['id' => $this->coupon_id, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id]);
        if (!$coupon) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '优惠券不存在');
        }
        if ($coupon->is_limit_total) {
            if ($coupon->total <= 0) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '优惠券已被领完');
            }
        }
        if ($coupon->user_total_limit) {
            $user_num = UserCoupon::find()->where(['user_id' => \Yii::$app->user->identity->id, 'coupon_id' => $this->coupon_id])->count();
            if ($user_num >= $coupon->user_total) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '领取已上限');
            }
        }
        $t = \Yii::$app->db->beginTransaction();
        $userCoupon = new UserCoupon();
        $userCoupon->mall_id = \Yii::$app->mall->id;
        $userCoupon->coupon_id = $this->coupon_id;
        $userCoupon->price = $coupon->price;
        $userCoupon->discount = $coupon->discount;
        $userCoupon->user_id = \Yii::$app->user->identity->id;
        $userCoupon->mch_id = $coupon->mch_id;
        if ($coupon->time_type == 1) {
            $userCoupon->start_at = time();
            $userCoupon->end_at = $coupon->end_at;
        } else {
            $userCoupon->start_at = time();
            $userCoupon->end_at = $coupon->day * 24 * 60 * 60 + time();
        }
        $userCoupon->type = $coupon->type;
        $userCoupon->is_goods_limit = $coupon->is_goods_limit;
        if (!$userCoupon->save()) {
            $t->rollBack();
            return $this->responseErrorMsg($userCoupon);
        }
        if ($coupon->is_limit_total) {
            $coupon->total -= 1;
            if (!$coupon->save()) {
                $t->rollBack();
                return $this->responseErrorMsg($coupon);
            }
        }
        $t->commit();


        return $this->apiResponse(ApiCode::CODE_SUCCESS, '领取成功');
    }


    public function getUserCouponList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = UserCoupon::find()
            ->alias('uc')
            ->where(['uc.mall_id' => \Yii::$app->mall->id, 'uc.user_id' => \Yii::$app->user->identity->id]);
        if ($this->status) {
            $query->andWhere(['uc.status' => $this->status]);
        }

        $list = $query->page($pagination, $this->limit, $this->page)
            ->orderBy('uc.status asc,uc.created_at desc')
            ->select('uc.*')
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['date'] = '使用日期:' . DateHelper::format($item['start_at']) . '~' . DateHelper::format($item['end_at']);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }

    public function getCouponList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = Coupon::find()->alias('c')->where(['c.mall_id' => \Yii::$app->mall->id, 'c.is_delete' => 0, 'c.is_join' => 1]);
        if ($this->gid) {
            $gids = GoodsCoupon::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'goods_id' => $this->gid])->select('coupon_id')->column();
            if ($this->gid) {
                $query->andWhere(['or', ['c.id' => $gids], ['c.is_goods_limit' => 0]]);
            } else {
                $query->andWhere(['c.is_goods_limit' => 0]);
            }
        }

        $list = $query->page($pagination, $this->limit, $this->page)->orderBy('c.created_at desc')->asArray()->all();

        foreach ($list as &$item) {

            $item['status'] = 0;
            if ($item['time_type']) {
                $item['date'] = '使用日期:' . DateHelper::format($item['start_at']) . '~' . DateHelper::format($item['end_at']);
            } else {
                $item['date'] = '领取后' . $item['day'] . '天内使用';
            }
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }
}