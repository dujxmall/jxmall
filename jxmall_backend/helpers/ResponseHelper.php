<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-17
 * Time: 10:08
 */

namespace app\helpers;


class ResponseHelper
{

    public static function send($code = 0, $msg = '', $data = [])
    {
        if (!$msg) {
            $msg = '数据请求成功';
        }
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    public static function baseSend(array $data)
    {
        return self::send($data['code'],$data['msg'],$data['data']);
    }

}