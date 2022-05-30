<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/21
 * Time: 11:36
 * Note:
 */

class m220528_193620_outside_link extends \app\core\Migration
{

    public function safeUp()
    {
        $this->createTable(
            '{{%outside_link}}',
            [
                'id' => $this->primaryKey(11),
                'mall_id' => $this->integer(11)->defaultValue(0)->comment('mall_id'),
                'link' => $this->string(512)->defaultValue('')->comment('外部链接地址'),
                'name' => $this->string(62)->defaultValue('')->comment('链接名称'),
            ]
        );

    }
}