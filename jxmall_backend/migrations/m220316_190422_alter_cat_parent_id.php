<?php

use yii\db\Schema;
use yii\db\Migration;

class m220316_190422_alter_cat_parent_id extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->alterColumn(\app\models\Cat::tableName(), 'parent_id', $this->integer(11)->defaultValue(0)->comment('上级分类'));

    }

    public function safeDown()
    {

    }
}
