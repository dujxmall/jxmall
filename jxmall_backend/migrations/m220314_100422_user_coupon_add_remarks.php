<?php

use yii\db\Schema;
use yii\db\Migration;

class m220314_100422_user_coupon_add_remarks extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {

        $this->addColumn(\app\models\UserCoupon::tableName(), 'remarks', $this->string(128)->defaultValue('')->comment('备注'));

    }


}
