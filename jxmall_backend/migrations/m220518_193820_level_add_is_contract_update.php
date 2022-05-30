<?php

use yii\db\Schema;
use yii\db\Migration;

class m220518_193820_level_add_is_contract_update extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn(\app\models\Level::tableName(), 'is_contract_update', $this->tinyInteger(1)->defaultValue(0)->comment('升级之前是否需要签订合同'));
        $this->addColumn(\app\models\Level::tableName(), 'contract_tpl_id', $this->string(64)->defaultValue('')->comment('合同模板ID'));
    }

    public function safeDown()
    {

    }
}
