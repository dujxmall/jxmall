<?php

use yii\db\Schema;
use yii\db\Migration;

class m220512_203820_plugin_add_description extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }


    public function safeUp()
    {
        $this->addColumn(\app\models\Plugin::tableName(), 'description', $this->string(255)->defaultValue('')->comment('介绍'));

    }

    public function safeDown()
    {

    }
}
