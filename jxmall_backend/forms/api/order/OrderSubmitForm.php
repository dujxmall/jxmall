<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-17
 * Time: 17:22
 */

namespace app\forms\api\order;


use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\IntegralLogHelper;
use app\helpers\JobHelper;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\helpers\SerializeHelper;
use app\jobs\order\OrderSubmitJob;
use app\jobs\order\UnionOrderAutoCancelJob;
use app\models\BaseModel;
use app\models\Cart;
use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\models\Goods;
use app\models\GoodsAttr;
use app\models\Order;
use app\models\UnionOrder;
use app\models\UserAddress;
use app\models\UserCoupon;
use yii\helpers\Json;

class OrderSubmitForm extends BaseModel
{

    public $mch_list;//商家商品列表
    public $address;
    public $pay_type;
    public $address_id;
    public $user_id;
    public $mall_id;


    public function rules()
    {
        return [
            [['mch_list', 'address'], 'trim'],
            [['mch_list', 'address'], 'string'],
            [['pay_type', 'address_id'], 'integer']
        ]; // TODO: Change the autogenerated stub
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-17
     * @Time: 17:43
     * @Note:提交订单
     */
    public function submit()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $this->user_id = \Yii::$app->user->identity->id;
        $this->mall_id = \Yii::$app->mall->id;
        $mch_list = SerializeHelper::decode($this->mch_list);
        if (!$mch_list) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '订单提交错误');
        }
        $this->mch_list = $mch_list;
        $user_address = UserAddress::findOne($this->address_id);
        if (!$user_address) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '收货地址不存在！');
        }
        $order_preview_data = CacheHelper::get("order_preview_uid_" . \Yii::$app->user->identity->id);//缓存三分钟
        if (!$order_preview_data) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '预下单信息不存在或已过期,请重新下单！');
        }
        $order_preview_data = Json::decode($order_preview_data);

        $t = \Yii::$app->db->beginTransaction();

        $userCoupon = null;
        if ($order_preview_data['user_coupon_id']) {
            $userCoupon = UserCoupon::findOne(['status' => 0, 'id' => $order_preview_data['user_coupon_id'], 'is_delete' => 0]);
            if (!$userCoupon) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '优惠券不存在或已过期,请重新下单！');
            }
        }
        $total_price = 0;
        $is_use_coupon = false;
        $unionOrder = new UnionOrder(); //多个订单合并下单
        $unionOrder->mall_id = $this->mall_id;
        $unionOrder->user_id = $this->user_id;
        $unionOrder->union_no = UnionOrder::generateOrderNo($this->user_id);
        if (!$unionOrder->save()) {
            return $this->responseErrorMsg($unionOrder);
        }
        $pay_price = 0;
        $goods_stock_arr = [];
        foreach ($this->mch_list as $i => $mch) {
            $total_order_price = 0;
            $order = new Order();
            $order->coupon_cut_price = 0;
            $order->user_id = $this->user_id;
            $order->mall_id = $this->mall_id;
            $order->mch_id = $mch['mch_id'];
            $order->province_code = $user_address->province_code;
            $order->city_code = $user_address->city_code;
            $order->county_code = $user_address->county_code;
            $order->address = $this->address;
            if ($userCoupon) {
                if ($mch['mch_id'] == $userCoupon->mch_id) {
                    $order->user_coupon_id = $userCoupon->id;
                    $order->use_coupon_goods_id = $order_preview_data['use_coupon_goods_id'] ?? 0;
                    $order->coupon_cut_price = $order_preview_data['coupon_cut_price'] ?? 0;
                }
            }
            if ($user_address->town_code) {
                $order->town_code = $user_address->town_code;
            }
            $order->name = $user_address->name;
            $order->mobile = $user_address->mobile;
            if ($mch['remarks']) {
                $order->remarks = $mch['remarks'];
            }
            if (!$order->save()) {
                $t->rollBack();
                return $this->responseErrorMsg($order);
            }
            $common_order = new CommonOrder();
            $common_order->mall_id = $order->mall_id;
            $common_order->user_id = $order->user_id;
            $common_order->order_id = $order->id;
            $common_order->order_no = CommonOrder::generateOrderNo($this->user_id);;
            $common_order->order_class = Order::class;
            $common_order->union_no = $unionOrder->union_no;
            $common_order->order_type = CommonOrder::TYPE_MALL;
            if (!$common_order->save()) {
                $t->rollBack();
                return $this->responseErrorMsg($common_order);
            }
            if ($mch['is_use_integral']) {
                $order->is_use_integral = $mch['is_use_integral'];
                $order->use_integral = $mch['use_integral'] ?? 0;
                $order->integral_cut_price = $mch['integral_cut_price'] ?? 0;
                try {
                    IntegralLogHelper::sub($this->user_id, $order->use_integral, '积分抵扣订单：' . $common_order->order_no);
                } catch (\Exception $e) {
                    $t->rollBack();
                    return ResponseHelper::send(ApiCode::CODE_FAIL, $e->getMessage());
                }
            }
            $goods_list = $mch['goods_list'];
            $coupon_cut_price = 0;
            if ($order->user_coupon_id) {
                if ($order->use_coupon_goods_id == 0) {
                    $coupon_cut_price = $order->coupon_cut_price / count($goods_list);
                }
            }
            foreach ($goods_list as $goods_item) {
                $goods = Goods::getOne($goods_item['goods_id']);
                if (!$goods) {
                    $t->rollBack();
                    ResponseHelper::send(ApiCode::CODE_FAIL, '商品不存在！');
                }
                $goods->price = $goods->getLevelPrice(\Yii::$app->user->identity->level_id);
                if ($goods->is_limit) {
                    $limit_num = $goods->limit_num;
                    $num = CommonOrder::find()
                        ->alias('o')
                        ->innerJoin(['d' => CommonOrderDetail::tableName()], 'o.order_no=d.common_order_no')
                        ->andWhere(['o.user_id' => \Yii::$app->user->identity->id, 'o.is_delete' => 0])
                        ->andWhere(['d.goods_id' => $goods->id])->andWhere(['o.is_pay' => 1])->sum('d.num');
                    $num = $num ?? 0;
                    $num += $goods_item['num'];
                    if ($num > $limit_num) {
                        $t->rollBack();
                        return ResponseHelper::send(ApiCode::CODE_FAIL, "商品：{$goods->name},超出了购买限制");
                    }
                }
                $detail = new CommonOrderDetail();
                $detail->mall_id = $order->mall_id;
                $detail->user_id = $order->user_id;
                $detail->common_order_no = $common_order->order_no;
                $detail->common_order_id = $common_order->id;
                $detail->goods_id = $goods->id;
                $detail->num = $goods_item['num'];

                Cart::deleteCart($goods->id, $goods_item['goods_attr_id']);
                if (!$goods->is_attr) {
                    $stock = Goods::getStock($goods->id);
                    if (!$stock) {
                        $t->rollBack();
                        return ResponseHelper::send(ApiCode::CODE_FAIL, '商品：' . $goods->name . '，库存不足！111');
                    }
                }

                if ($goods->is_attr) {
                    $goods_attr = GoodsAttr::getOne($goods_item['goods_attr_id']);
                    if (!$goods_attr) {
                        $t->rollBack();
                        return ResponseHelper::send(ApiCode::CODE_FAIL, '商品：' . $goods->name . '，库存不足！');
                    }
                    $detail->attr = $goods_attr->attr_list;
                    $stock = Goods::getStock($goods_attr->goods_id, $goods_attr->id);
                    if ($stock >= $detail->num) {
                        $goods_stock_arr[] = [
                            'goods_id' => $goods->id,
                            'num' => $detail->num,
                            'attr_id' => $goods_attr->id
                        ];
                    } else {
                        //库存不足
                        $t->rollBack();
                        return ResponseHelper::send(ApiCode::CODE_FAIL, '商品：' . $goods->name . '，库存不足！');
                    }
                } else { //库存减少
                    if ($goods->stock >= $detail->num) {
                        $goods_stock_arr[] = [
                            'goods_id' => $goods->id,
                            'num' => $detail->num,
                            'attr_id' => 0
                        ];
                    } else {
                        $t->rollBack();
                        return ResponseHelper::send(ApiCode::CODE_FAIL, '商品：' . $goods->name . '，库存不足！222');
                    }
                }
                if (!$goods->is_attr) {
                    $detail->attr = Json::encode([['attr_group_name' => '规格', 'attr_name' => '默认']]);
                    $total_price += $goods->price * $detail->num;
                    $detail->total_price = $goods->price * $detail->num;
                    $detail->price = $goods->price;
                    $detail->pay_price = $goods->price * $detail->num;
                    $total_order_price += $goods->price * $detail->num;
                } else {
                    //此处还要处理多规格
                    if ($goods_attr) {//多规格的钱
                        $total_price += $goods_attr->price * $detail->num;
                        $total_order_price += $goods_attr->price * $detail->num;
                        $detail->total_price = $goods_attr->price * $detail->num;
                        $detail->pay_price = $goods_attr->price * $detail->num;
                        $detail->price = $goods_attr->price;
                    }
                }
                if ($goods_item['integral_price'] > 0) {
                    $detail->pay_price -= $goods_item['integral_price'];
                    $total_order_price -= $goods_item['integral_price'];
                    $total_price -= $goods_item['integral_price'];
                    $detail->integral_cut_price = $goods_item['integral_price'];
                    $detail->use_integral = $goods_item['use_integral'];
                }
                if (!$is_use_coupon) {
                    if ($order->user_coupon_id) {
                        if ($order->use_coupon_goods_id == $goods->id) {//针对某个商品的优惠券
                            $detail->price -= $order->coupon_cut_price;
                            $detail->coupon_cut_price = $order->coupon_cut_price;
                            $detail->user_coupon_id = $order->user_coupon_id;
                            $is_use_coupon = true;
                        }
                        if ($order->use_coupon_goods_id == 0) {
                            $detail->price -= $coupon_cut_price;
                            $detail->coupon_cut_price = $coupon_cut_price;
                            $detail->user_coupon_id = $order->user_coupon_id;
                            $is_use_coupon = true;
                        }
                    }
                }
                if (!$detail->save()) {
                    $t->rollBack();
                    return $this->responseErrorMsg($detail);
                }
            }
            if ($mch['express_price'] != $order_preview_data['mch_list'][$i]['express_price']) {
                $result = '订单信息与预下单信息不一致';
                //这个时候要想办法把库存加回来
                $t->rollBack();
                return ResponseHelper::send(ApiCode::CODE_FAIL, $result);
            }
            //还要处理运费
            $order->express_price = $mch['express_price'] ?? 0;
            $total_order_price += $order->express_price;
            $order->total_price = $total_order_price + $order->integral_cut_price;
            $order->pay_price = $total_order_price - $order->coupon_cut_price;
            $order->order_no = $common_order->order_no;
            if (!$order->save()) {
                //这个时候要想办法把库存加回来
                $t->rollBack();
                return $this->responseErrorMsg($order);
            }
            unset($goods);
            $total_price += $order->express_price;
            $common_order->pay_price = $order->pay_price;
            $common_order->total_price = $order->total_price;
            if (!$common_order->save()) {
                $t->rollBack();
                return $this->responseErrorMsg($common_order);
            }
            $pay_price += $common_order->pay_price;
        }
        $t->commit();
        $setting = OptionHelper::get('mall_setting', \Yii::$app->mall->id);
        if (!$setting['over_time']) {
            $setting['over_time'] = 5;
        }
        JobHelper::push(new UnionOrderAutoCancelJob(['order_id' => $unionOrder->id]), $setting['over_time'] * 60);
        $unionOrder->pay_price = $pay_price;
        $unionOrder->save();

        $this->decStock($goods_stock_arr);
        //返回合并订单ID
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['union_order_id' => $unionOrder->id, 'is_union_order' => 1]);
    }

    private function decStock($arr = [])
    {
        foreach ($arr as $item) {
            Goods::decStock($item['goods_id'], $item['num'], $item['attr_id']);
        }
    }
}