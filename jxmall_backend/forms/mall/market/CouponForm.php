<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-11
 * Time: 22:54
 */

namespace app\forms\mall\market;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\Coupon;
use app\models\GoodsCoupon;
use app\models\UserCoupon;

class CouponForm extends BaseModel
{
    public $name;
    public $is_limit_total;
    public $discount;
    public $price;
    public $time_type;
    public $total;
    public $day;
    public $start_at;
    public $end_at;
    public $is_join;
    public $user_total;
    public $is_goods_limit;
    public $type;
    public $user_total_limit;
    public $date_range;
    public $goods_list;
    public $id;
    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['id', 'limit', 'page', 'is_limit_total', 'time_type', 'total',
                'day', 'start_at', 'end_at', 'is_join', 'user_total', 'is_goods_limit', 'type', 'user_total_limit'], 'integer'],
            [['discount', 'price'], 'number'],
            [['name'], 'string', 'max' => 45],
            [['limit'], 'default', 'value' => 10],
            [['date_range'], 'safe'],
            [['goods_list'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        if ($this->id) {
            $coupon = Coupon::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
            if (!$coupon) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '优惠券不存在');
            }
        } else {
            $coupon = new Coupon();
            $coupon->mall_id = \Yii::$app->mall->id;
        }
        if ($this->time_type == 1) {
            if (!$this->date_range) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '请选择日期范围');
            }
            $this->start_at = strtotime($this->date_range[0]);
            $this->end_at = strtotime($this->date_range[1]);
        }
        $coupon->attributes = $this->attributes;

        if (!$coupon->save()) {
            return $this->responseErrorMsg($coupon);
        }
        if ($this->is_goods_limit) {
            if (!$this->goods_list) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '请选择商品');
            }
            $goods_list = SerializeHelper::decode($this->goods_list);
            OptionHelper::set('coupon_goods_list_' . $coupon->id, $goods_list, \Yii::$app->mall->id);
            foreach ($goods_list as $goods) {
                $goodsCoupon = GoodsCoupon::findOne(['goods_id' => $goods['id'], 'coupon_id' => $coupon->id, 'is_delete' => 0]);
                if (!$goodsCoupon) {
                    $goodsCoupon = new  GoodsCoupon();
                    $goodsCoupon->mall_id = $coupon->mall_id;
                    $goodsCoupon->coupon_id = $coupon->id;
                    $goodsCoupon->goods_id = $goods['id'];
                    $goodsCoupon->save();
                }
            }
        }

        UserCoupon::updateAll(['type' => $coupon->type, 'price' => $coupon->price, 'discount' => $coupon->discount, 'is_goods_limit' => $coupon->is_goods_limit], ['coupon_id' => $coupon->id, 'status' => 0, 'is_delete' => 0]);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        if ($this->id) {
            $coupon = Coupon::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
            if (!$coupon) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '优惠券不存在');
            }
        }
        $this->attributes = $coupon->attributes;
        $goods_list = [];
        $this->goods_list = [];
        if ($coupon->is_goods_limit) {
            $goods_list = OptionHelper::get('coupon_goods_list_' . $coupon->id, \Yii::$app->mall->id);
            $this->goods_list = $goods_list ?? [];
        }
        if ($coupon->time_type) {
            $this->date_range = [date('Y-m-d', $this->start_at), date('Y-m-d', $this->end_at)];
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['coupon' => $this->attributes]);
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        if ($this->id) {
            $coupon = Coupon::findOne(['is_delete' => 0, 'id' => $this->id, 'mall_id' => \Yii::$app->mall->id]);
            if (!$coupon) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '优惠券不存在');
            }
        }
        $coupon->is_delete = 1;
        if (!$coupon->save()) {
            return $this->responseErrorMsg($coupon);
        }
        UserCoupon::updateAll(['is_delete' => 1], ['coupon_id' => $this->id]);
        GoodsCoupon::updateAll(['is_delete' => 1], ['coupon_id' => $this->id]);

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '删除成功');
    }


    public function getList()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = Coupon::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        $list = $query->page($pagination, $this->limit, $this->page)->asArray()->all();
        foreach ($list as &$item) {
            if ($item['time_type']) {
                $date_range = date('Y-m-d', $item['start_at']) . '~' . date('Y-m-d', $item['end_at']);
                $item['date_range'] = $date_range;
            } else {
                $item['date_range'] = '领取后' . $item['day'];
            }
        }
        unset($item);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }
}