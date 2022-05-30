<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-25
 * Time: 12:00
 */

namespace app\helpers;


class ImageHelper
{
    public static function getUrl($name)
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web' . $name;
        }
        return \Yii::$app->request->hostInfo . '/web' . $name;
    }

    public static function getUrlOnPlugin($name)
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/plugins' . $name;
        }
        return \Yii::$app->request->hostInfo . '/plugins' . $name;
    }
}