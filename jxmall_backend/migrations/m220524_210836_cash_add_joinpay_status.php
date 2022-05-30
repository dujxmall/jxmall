<?php

use yii\db\Schema;
use yii\db\Migration;

class m220524_210836_cash_add_joinpay_status extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Cash::tableName(), 'joinpay_status', $this->tinyInteger(1)->defaultValue(0)->comment('0 未付款 1 已打款 2打款失败'));
    }

    public function safeDown()
    {
    }
}
