<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-17
 * Time: 15:34
 */

namespace app\helpers;


class DateHelper
{

    public static function format($timestamp, $format = 'Y-m-d H:i:s')
    {
        return date($format, $timestamp);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-01-05
     * @Time: 15:38
     * @Note:昨日
     */
    public static function yesterday_start_at()
    {
        return mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
    }


    public static function yesterday_end_at()
    {
        return mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
    }

    public static function month_start_at()
    {
        return mktime(0, 0, 0, date('m'), 1, date('Y'));
    }

    public static function week_start_at()
    {
        return mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
    }

    public static function today_start_at()
    {
        return strtotime(date('Y-m-d', time()));
    }

}
