<?php
/**
 * Created by PhpStorm.
 * Author:ganxh
 * User: cp
 * Date: 2021/9/30
 * Time: 14:06
 */

namespace app\helpers;


class EnvHelper
{
    const ENV_PREFIX = 'JXMALL_';

    public static function loadFile($filePath)
    {
        if (!file_exists($filePath)) {
            return [];
        };
        //返回二位数组
        return parse_ini_file($filePath, TRUE);
    }

    public static function get($name, $default = null)
    {
        $result = getenv(static::ENV_PREFIX . strtoupper(str_replace('.', '_', $name)));

        if (false !== $result) {
            if ('false' === $result) {
                $result = false;
            } elseif ('true' === $result) {
                $result = true;
            }
            return $result;
        }
        return $default;
    }


}
