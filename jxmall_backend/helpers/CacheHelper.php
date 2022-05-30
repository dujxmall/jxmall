<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 19:40
 */

namespace app\helpers;

use yii\base\BaseObject;

class CacheHelper extends BaseObject
{

    public static function getKeyName($name)
    {
        $key = md5(\Yii::$app->db->dsn . $name);
        return $key;
    }


    public static function set($name, $data, $duration = null)
    {
        $key = self::getKeyName($name);
        \Yii::$app->cache->set($key, $data, $duration ?? null);
    }

    public static function get($name)
    {
        $key = self::getKeyName($name);
        $data = \Yii::$app->cache->get($key, null);
        return $data;
    }

    public static function remove($name)
    {
        $key = self::getKeyName($name);
        \Yii::$app->cache->delete($key);
    }

}
