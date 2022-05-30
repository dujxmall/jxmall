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


use app\models\Option;
use yii\base\BaseObject;
use yii\helpers\Json;

class OptionHelper extends BaseObject
{
    public static function set($name, $data, $mall_id = 0, $duration = null)
    {
        $model = \app\mongo\JxmallOption::findOne(['name' => $name, 'mall_id' => $mall_id]);
        if (!$model) {
            $model = new \app\mongo\JxmallOption();
        }

        if (is_array($data)) {//如果这三个同时存在就说明了是来自model里面的
            if (array_key_exists('page', $data) && array_key_exists('limit', $data) && array_key_exists('pagination', $data)) {
                unset($data['page']);
                unset($data['limit']);
                unset($data['pagination']);
            }
        }
        $model->name = $name;
        $model->mall_id = $mall_id;
        $model->value = $data;
        $model->save();
        //CacheHelper::set($key, $data, null);
        return true;
    }

    public static function get($name, $mall_id = 0)
    {
        $model = \app\mongo\JxmallOption::findOne(['name' => $name, 'mall_id' => intval($mall_id)]);
        if (!$model) {
            return null;
        } else {
            $res = $model->value;
        }
        if ($res) {
            return $res;
        } else {
            return null;
        }
    }

    public static function remove($name, $mall_id = 0)
    {
        $model = \app\mongo\JxmallOption::findOne(['name' => $name, 'mall_id' => $mall_id]);
        if (!$model) {
            return true;
        }
        $model->delete();
        return true;
    }


}
