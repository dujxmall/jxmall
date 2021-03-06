<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-30
 * Time: 23:09
 */

namespace app\forms\api\order;


use app\core\ApiCode;
use app\helpers\SerializeHelper;
use app\helpers\WechatHelper;
use app\models\BaseModel;
use app\models\CommonOrder;
use app\models\CommonOrderDetail;
use app\models\Order;


/**
 * Class OrderListForm
 * @package app\forms\api\order
 * @Notes订单列表
 */
class OrderListForm extends BaseModel
{

    public $status;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit', 'status'], 'integer'],
            [['limit'], 'default', 'value' => 10]
        ]; // TODO: Change the autogenerated stub
    }

    public function getList()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $query = Order::find()->where(['is_delete' => 0, 'user_id' => \Yii::$app->user->identity->id]);
        if ($this->status == 0) { //待付款
            $query->andWhere(['status' => Order::STATUS_NOT_PAY]);
        }
        if ($this->status == 1) {//待发货
            $query->andWhere(['status' => Order::STATUS_NOT_SEND]);
        }
        if ($this->status == 2) {//待收货
            $query->andWhere(['status' => Order::STATUS_NOT_CONFIRM]);
        }
        if ($this->status == 3) {//已收货
            $query->andWhere(['status' => Order::STATUS_IS_CONFIRM]);
        }
        if ($this->status == 4) {//已完成 待评价
            $query->andWhere(['status' => Order::STATUS_IS_COMPLETE]);
        }
        if ($this->status == 5) {//已完成 待评价
            $query->andWhere(['status' => Order::STATUS_CANCEL]);
        }
        $query->page($pagination, $this->limit, $this->page);
        $list = $query->orderBy('created_at DESC')
            ->asArray()->all();
        foreach ($list as &$item) {
            $detail_list = CommonOrderDetail::find()->with('goods')->where(['common_order_no' => $item['order_no']])->asArray()->all();
            foreach ($detail_list as &$detail) {
                if ($detail['attr']) {
                    $detail['attr'] = SerializeHelper::decode($detail['attr']);
                } else {
                    $detail['attr'] = [];
                }
            }
            $item['detail_list'] = $detail_list;
            $item['created_at'] = date('Y-m-d H:i:s', $item['created_at']);
            $commonOrder = CommonOrder::findOne(['order_id' => $item['id']]);
            if ($commonOrder) {
                $item['common_order_id'] = $commonOrder->id;

            } else {
                $item['common_order_id'] = 0;
            }
            unset($detail);
        }
        unset($item);
        unset($detail_list);

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list, 'pagination' => $pagination]);
    }

}
