<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/21
 * Time: 11:36
 * Note:
 */

class m220526_113620_wechat_order_add_pay_data extends \app\core\Migration
{

    public function safeUp()
    {
        $this->addColumn(\app\models\WechatOrder::tableName(), 'is_pay', $this->tinyInteger(1)->defaultValue(0)->comment('是否支付'));

        $this->addColumn(\app\models\WechatOrder::tableName(), 'pay_time', $this->timestamp()->null()->comment('支付时间'));

        $this->addColumn(\app\models\WechatOrder::tableName(), 'transaction_id', $this->string(64)->notNull()->defaultValue('')->comment('交易ID'));

    }
}