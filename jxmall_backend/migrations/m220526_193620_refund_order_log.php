<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/21
 * Time: 11:36
 * Note:
 */

class m220526_193620_refund_order_log extends \app\core\Migration
{

    public function safeUp()
    {
        $this->createTable(
            '{{%refund_order_log}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->defaultValue(0)->comment('mall_id'),
                'order_no' => $this->string(45)->defaultValue('')->comment('订单号'),
                'refund_order_no' => $this->string(45)->defaultValue('')->comment('退款单号'),
                'refund_price' => $this->decimal(10, 2)->defaultValue(0)->comment('退款金额'),
                'remark' => $this->string(255)->defaultValue('')->comment('备注'),
                'from_pay_type' => $this->string(45)->defaultValue('wechat')->comment('退款来源 wehcat join')
            ]
        );

    }
}