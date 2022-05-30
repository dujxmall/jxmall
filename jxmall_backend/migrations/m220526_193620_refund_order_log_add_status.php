<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/21
 * Time: 11:36
 * Note:
 */

class m220526_193620_refund_order_log_add_status extends \app\core\Migration
{

    public function safeUp()
    {
        $this->addColumn(\app\models\RefundOrderLog::tableName(), 'status', $this->tinyInteger(1)->defaultValue(0)->comment('退款状态 1 成功 2失败'));
        $this->addColumn(\app\models\RefundOrderLog::tableName(), 'message', $this->string(5120)->defaultValue('')->comment('回调通知的消息'));


    }
}