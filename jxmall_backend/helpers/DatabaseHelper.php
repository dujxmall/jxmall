<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-01-07
 * Time: 13:08
 */

namespace app\helpers;


class DatabaseHelper
{
    public static function getDbName()
    {
        return \Yii::$app->db->dsn;
    }
}