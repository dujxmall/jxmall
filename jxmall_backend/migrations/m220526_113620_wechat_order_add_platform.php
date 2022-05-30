<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/21
 * Time: 11:36
 * Note:
 */

class m220526_113620_wechat_order_add_platform extends \app\core\Migration
{

    public function safeUp()
    {
        $this->addColumn(\app\models\WechatOrder::tableName(), 'platform', $this->string(45)->defaultValue('')->comment('所在端口'));


    }
}