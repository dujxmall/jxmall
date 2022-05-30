<?php

use yii\db\Schema;
use yii\db\Migration;

class m220501_193820_user_upgrade_add_time_at extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\UserUpgradeLog::tableName(), 'time_at', $this->timestamp()->null()->comment('升级时间'));


    }

    public function safeDown()
    {

    }
}
