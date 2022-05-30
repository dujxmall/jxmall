<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-04-20
 * Time: 22:33
 */

namespace app\forms\mall\order;


use app\core\ApiCode;
use app\helpers\ExpressHelper;
use app\helpers\ResponseHelper;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\CommonOrderDetail;
use app\models\ExpressLog;
use app\models\Order;
use app\models\OrderDetail;

class ExcelSendForm extends BaseModel
{


    public $excel_data;

    public function rules()
    {
        return [
            [['excel_data'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function send()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $list = SerializeHelper::decode($this->excel_data);
        $t = \Yii::$app->db->beginTransaction();
        foreach ($list as $item) {
            $order = Order::getOne($item['订单ID']);
            if ($order) {
                if ($order->status == Order::STATUS_NOT_CONFIRM) {
                    $t->rollBack();
                    return ResponseHelper::send(ApiCode::CODE_FAIL, '订单：' . $order->order_no . '已发货，请检查');
                }
                $order->express_log_id = 0;
                $order->send_type = 2;
                $order->is_send = 1;
                $order->send_at = time();
                $order->status = Order::STATUS_NOT_CONFIRM;
                if (!$order->save()) {
                    $t->rollBack();
                    return $this->responseErrorMsg($order);
                }

                $log = new ExpressLog();
                $log->mall_id = \Yii::$app->mall->id;
                $log->express_name = $item['快递名称'];
                $log->express_no = $item['快递单号'];
                $express = $this->getExpress($item['快递名称']);
                if (!$express) {
                    $t->rollBack();
                    return ResponseHelper::send(ApiCode::CODE_FAIL, '快递不存在！');
                }
                $log->express_code = $express['code'];
                $log->name = $item['收货人'];
                $log->mobile = $item['电话'];
                $log->address = $item['收货地址'];
                $log->order_id = $order->id;
                $log->order_no = $order->order_no;
                $log->is_all = 1;
                $goods_num = Order::find()->alias('o')->leftJoin(['od' => CommonOrderDetail::tableName()], 'od.common_order_no=o.order_no')->where(['o.id' => $order->id])->sum('od.num');
                $log->num = $goods_num ?? 0;
                if (!$log->save()) {
                    $t->rollBack();
                    return $this->responseErrorMsg($log);
                }
            } else {
                $t->rollBack();
                return ResponseHelper::send(ApiCode::CODE_FAIL, '订单不存在！');
            }
        }
        $t->commit();
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '发货完成！');
    }


    private function getExpress($express_name)
    {
        $list = ExpressHelper::express();
        foreach ($list as $item) {
            if ($item['name'] == $express_name) {
                return $item;
            }
        }

        return null;
    }
}