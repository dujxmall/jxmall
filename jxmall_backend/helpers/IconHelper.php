<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 13:39
 */

namespace app\helpers;


class IconHelper
{
    public static function wechat()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/wechat.png';
        }
        return \Yii::$app->request->hostInfo . '/web/assets/icons/wechat.png';
    }

    public static function bank()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/bank.png';
        }
        return \Yii::$app->request->hostInfo . '/web/assets/icons/bank.png';
    }

    public static function alipay()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/alipay.png';
        }

        return \Yii::$app->request->hostInfo . '/web/assets/icons/alipay.png';
    }

    public static function balance()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/balance.png';
        }

        return \Yii::$app->request->hostInfo . '/web/assets/icons/balance.png';
    }

    public static function mpwx()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/mpwx.png';
        }

        return \Yii::$app->request->hostInfo . '/web/assets/icons/mpwx.png';
    }

    public static function mobile()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/mobile.png';
        }

        return \Yii::$app->request->hostInfo . '/web/assets/icons/mobile.png';
    }

    public static function wxapp()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/wechat.png';
        }
        return \Yii::$app->request->hostInfo . '/web/assets/icons/wechat.png';
    }

    public static function sysPay()
    {
        if (IS_WE7_APP) {
            return \Yii::$app->request->hostInfo . '/addons/' . WE7_MODULE_NAME . '/web/assets/icons/sys-pay.png';
        }
        return \Yii::$app->request->hostInfo . '/web/assets/icons/sys-pay.png';
    }
}