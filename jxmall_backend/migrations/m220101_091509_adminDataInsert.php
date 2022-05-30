<?php

use yii\db\Schema;
use yii\db\Migration;

class m220101_091509_adminDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%admin}}',
            ["id", "name", "mall_count", "is_delete", "created_at", "updated_at", "deleted_at", "is_expire", "end_at", "is_has_expire", "admin_type", "password", "username", "access_token", "login_ip", "auth_key", "mobile", "created_by", "we7_uid", "mch_id"],
            [
                [
                    'id' => '1',
                    'name' => 'admin',
                    'mall_count' => '0',
                    'is_delete' => '0',
                    'created_at' => time(),
                    'updated_at' => time(),
                    'deleted_at' => '0',
                    'is_expire' => '0',
                    'end_at' => null,
                    'is_has_expire' => '0',
                    'admin_type' => '0',
                    'password' => Yii::$app->security->generatePasswordHash('jxmall'),
                    'username' => 'admin',
                    'access_token' => Yii::$app->security->generateRandomString(),
                    'login_ip' => '',
                    'auth_key' => Yii::$app->security->generateRandomString(),
                    'mobile' => null,
                    'created_by' => '0',
                    'we7_uid' => '0',
                    'mch_id' => '0',
                ]
            ]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%admin}} CASCADE');
    }
}
