<?php

use yii\db\Schema;
use yii\db\Migration;

class m220128_090422_user_upgrade_log extends \app\core\Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB CHARSET=utf8mb4';

        $this->createTable(
            '{{%user_upgrade_log}}',
            [
                'id'=> $this->primaryKey(11),
                'mall_id'=> $this->integer(11)->notNull(),
                'user_id'=> $this->integer(11)->notNull(),
                'before_level_id'=> $this->integer(11)->notNull()->defaultValue(-1),
                'after_level_id'=> $this->integer(11)->notNull()->defaultValue(-1),
                'reason_by'=> $this->integer(11)->notNull()->comment('0 购物满   1购买商品 2直推会员'),
                'marks'=> $this->string(45)->notNull()->defaultValue(''),

            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%user_upgrade_log}}');
    }
}
