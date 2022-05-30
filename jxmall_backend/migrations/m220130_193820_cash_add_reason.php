<?php

use yii\db\Schema;
use yii\db\Migration;

class m220130_193820_cash_add_reason extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Cash::tableName(), 'reason', $this->string(512)->defaultValue('')->comment('原因'));
    }

    public function safeDown()
    {

    }
}
