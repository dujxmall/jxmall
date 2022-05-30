<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-10-08
 * Time: 20:50
 */

namespace app\helpers;


use yii\console\Application;

class ConsoleHelper
{

    public static function getApp()
    {
        $config = require ServerHelper::getYiiBasePath() . '/config/console.php';
        return new  Application($config);
    }
}