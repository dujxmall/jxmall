<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-29
 * Time: 11:37
 */

namespace app\helpers;


class MallSettingHelper
{

    public static function getLogo($mall_id)
    {

        $option = OptionHelper::get('mall_setting', $mall_id);
        if ($option && $option['logo']) {
            return $option['logo'];
        }
        return null;
    }

}