<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-02
 * Time: 22:18
 */

namespace app\forms\mall\order;

use app\core\ApiCode;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\CommonOrderDetail;
use app\models\ExpressLog;
use app\models\Order;


class OrderSendForm extends BaseModel
{

    public $order_id;
    public $express_code;
    public $express_name;
    public $express_no;
    public $send_type;//0 整单发货  1 分包发货 2 无需物流
    public $send_list;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'send_type'], 'integer'],
            [['send_list'], 'safe'],
            [['express_code', 'express_name', 'express_no'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $order = Order::findOne(['is_delete' => 0, 'id' => $this->order_id, 'mall_id' => \Yii::$app->mall->id]);
        if (!$order) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '订单不存在!');
        }
        $is_all = 1;
        if ($this->send_type == 2) {
            $is_all = 1;
            $order->express_log_id = 0;
            $order->send_type = 2;
            $order->is_send = 1;
            $order->send_at = time();
            $order->status = Order::STATUS_NOT_CONFIRM;
            $order->save();
            return $this->apiResponse(ApiCode::CODE_SUCCESS, '发货成功!');
        } else {
            $is_all = 0;
        }
        //需要判断是否是快递 不是快递直接改变状态
        $log = new ExpressLog();
        $log->mall_id = \Yii::$app->mall->id;
        $log->express_name = $this->express_name;
        $log->express_no = $this->express_no;
        $log->express_code = $this->express_code;
        $log->name = $order->name;
        $log->mobile = $order->mobile;
        $log->address = $order->address;
        $log->order_id = $this->order_id;
        $log->order_no = $order->order_no;
        $log->is_all = $is_all;
        if ($this->send_type == 1) {
            $log->order_detail_id = SerializeHelper::encode($this->send_list);
            $goods_num = CommonOrderDetail::find()->where(['is_delete' => 0, 'id' => $this->send_list])->sum('num');
        }
        if ($this->send_type == 0) {
            $goods_num = Order::find()->alias('o')->leftJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')->where(['o.id' => $this->order_id])->sum('od.num');
        }

        $log->num = $goods_num;
        if (!$log->save()) {
            return $this->responseErrorMsg($log);
        }
        if ($this->send_type == 1) {
            CommonOrderDetail::updateAll(['is_send' => 1, 'express_log_id' => $log->id], ['is_send' => 0, 'common_order_no' => $order->order_no, 'is_delete' => 0, 'id' => $this->send_list]);
            $exist = CommonOrderDetail::find()->where(['common_order_no' => $order->order_no, 'is_send' => 0, 'is_delete' => 0])->exists();
            if (!$exist) {
                if (!$order->is_send) {
                    $order->send_type = 1;
                    $order->is_send = 1;
                    $order->status = Order::STATUS_NOT_CONFIRM;
                    $order->send_at = time();
                    $order->save();
                }
            }
        } else {
            $order->express_log_id = $log->id;
            $order->send_type = 0;
            $order->is_send = 1;
            $order->send_at = time();
            $order->status = Order::STATUS_NOT_CONFIRM;
            $order->save();
            CommonOrderDetail::updateAll(['is_send' => 1, 'express_log_id' => $log->id], ['is_send' => 0, 'common_order_no' => $order->order_no, 'is_delete' => 0]);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '发货成功');
    }

}