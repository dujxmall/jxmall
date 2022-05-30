<?php

use yii\db\Schema;
use yii\db\Migration;

class m220511_190422_coupon_add_is_expire extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Coupon::tableName(), 'is_expire', $this->tinyInteger(1)->defaultValue(0)->comment('是否过期'));

    }

    public function safeDown()
    {

    }
}
