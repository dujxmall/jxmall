<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:29
 */

namespace app\core;

use yii\console\Request;

class ConsoleRequest extends Request
{
    public $enableCsrfCookie=false;



    public function getUserIp()
    {
        return '0.0.0.0';
    }



    public function getCsrfToken()
    {
        return null;
    }
    public static function getAbsoluteUrl(){

        return false;

    }

    public static function getIsAjax(){

        return false;
    }

    public static function getMethod(){

        return false;

    }
}
