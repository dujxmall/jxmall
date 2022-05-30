<?php

use yii\db\Schema;
use yii\db\Migration;

class m220313_100422_user_coupon_add_code extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\UserCoupon::tableName(), 'code', $this->string(45)->defaultValue('')->comment('优惠券码'));

    }


}
