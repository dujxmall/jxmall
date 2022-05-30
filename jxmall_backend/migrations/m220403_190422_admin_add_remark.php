<?php

use yii\db\Schema;
use yii\db\Migration;

class m220403_190422_admin_add_remark extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Admin::tableName(), 'remark', $this->string(255)->defaultValue('')->comment('备注'));
    }

    public function safeDown()
    {

    }
}
