<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/21
 * Time: 11:36
 * Note:
 */

class m220525_113620_wechat_order extends \app\core\Migration
{

    public function safeUp()
    {
        $this->createTable(
            '{{%wechat_order}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->defaultValue(0)->comment('mall_id'),
                'order_no' => $this->string(45)->defaultValue('')->comment('订单号'),
                'pay_price' => $this->decimal(10, 2)->defaultValue(0)->comment('支付金额'),
                'pay_type' => $this->string(45)->defaultValue('wechat')->comment('支付方式 wehcat join')
            ]
        );

    }
}