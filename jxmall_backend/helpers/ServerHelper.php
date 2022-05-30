<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-27
 * Time: 23:10
 */

namespace app\helpers;


class ServerHelper
{

    public static function getBaseUrl()
    {
        $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] ==
                'https')) ? 'https://' : 'http://';
        $url = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
        if ($protocol) {
            $url = str_replace('http://', $protocol, $url);
            $url = str_replace('https://', $protocol, $url);
        }
        return $url;
    }

    public static function getHost()
    {
        $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] ==
                'https')) ? 'https://' : 'http://';
        $url = \Yii::$app->request->hostInfo;

        if ($protocol) {
            $url = str_replace('http://', $protocol, $url);
            $url = str_replace('https://', $protocol, $url);
        }
        if (IS_WE7_APP) {
            $url .= "/addons/" . WE7_MODULE_NAME;
        }
        return $url;
    }

    public static function getUserIp()
    {
        return \Yii::$app->request->remoteIP;
    }


    public static function getYiiBasePath()
    {

        return \Yii::$app->basePath;
    }
}
