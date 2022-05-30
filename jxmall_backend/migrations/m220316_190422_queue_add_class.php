<?php

use yii\db\Schema;
use yii\db\Migration;

class m220316_190422_queue_add_class extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn("{{%queue}}", 'class', $this->string(128)->defaultValue('')->comment('class'));

    }

    public function safeDown()
    {

    }
}
