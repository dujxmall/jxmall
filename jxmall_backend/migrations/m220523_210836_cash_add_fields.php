<?php

use yii\db\Schema;
use yii\db\Migration;

class m220523_210836_cash_add_fields extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Cash::tableName(), 'is_joinpay', $this->tinyInteger(1)->defaultValue(0)->comment('是否是汇聚支付'));
        $this->addColumn(\app\models\Cash::tableName(), 'joinpay_desc', $this->string(5120)->defaultValue('')->comment('汇聚支付返回的信息'));
    }

    public function safeDown()
    {
    }
}
