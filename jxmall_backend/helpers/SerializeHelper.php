<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:57
 */
namespace app\helpers;

class SerializeHelper extends \yii\base\Component
{
    public static function encode($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public static function decode($value)
    {
        $res = json_decode($value, true);
        if ($res === null) {
            if (json_last_error() == JSON_ERROR_NONE) {
                return $res;
            }
            if (json_last_error() != JSON_ERROR_SYNTAX) {
                $error = json_last_error_msg();
                throw new \InvalidArgumentException("{$error}: `{$value}` cannot be decoded!");
            }
            $res = unserialize($value);
            if ($res === false) {
                $value = preg_replace_callback(
                    '/s:([0-9]+):\"(.*?)\";/',
                    function ($matches) {
                        return "s:" . strlen($matches[2]) . ':"' . $matches[2] . '";';
                    },
                    $value
                );
                $res = unserialize($value);
                if ($res === false) {
                    throw new \InvalidArgumentException("`{$value}` cannot be unserialized!");
                }
            }
        }
        return $res;
    }
}
