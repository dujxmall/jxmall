<?php

use yii\db\Schema;
use yii\db\Migration;

class m220314_100422_user_birthday_add_year_month_day extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\User::tableName(), 'year', $this->integer(5)->defaultValue(0)->comment('年'));
        $this->addColumn(\app\models\User::tableName(), 'month', $this->tinyInteger(2)->defaultValue(0)->comment('月'));
        $this->addColumn(\app\models\User::tableName(), 'day', $this->tinyInteger(2)->defaultValue(0)->comment('日'));
    }


}
