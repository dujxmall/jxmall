<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-07-31
 * Time: 22:23
 */

namespace app\core;


use app\helpers\WechatHelper;
use jianyan\easywechat\Wechat;
use yii\base\BaseObject;
use yii\base\Exception;

class App extends BaseObject
{

    public static function warning($msg)
    {
        \Yii::warning($msg);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-07-31
     * @Time: 22:30
     * @Note:
     * @param null $mall_id
     * @return Wechat
     * @throws Exception
     */
    public static function wechat($mall_id = null)
    {
        if (!$mall_id) {
            throw new Exception('未知的商城ID');
        }
        WechatHelper::init($mall_id);
        return \Yii::$app->wechat;
    }

}
