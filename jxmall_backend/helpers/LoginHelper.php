<?php
/**
 * Created by PhpStorm.
 * Author:ganxh
 * User: cp
 * Date: 2021/11/22
 * Time: 10:28
 */

namespace app\helpers;


class LoginHelper
{

    public static function getAuthCode()
    {
        $code_key = ServerHelper::getUserIp() . '_ADMIN_LOGIN_CAPTCHA';
        return CacheHelper::get($code_key);
    }

    public static function setAuthCode($code)
    {
        $code_key = ServerHelper::getUserIp() . '_ADMIN_LOGIN_CAPTCHA';
        return CacheHelper::set($code_key, $code, 30);
    }
}
