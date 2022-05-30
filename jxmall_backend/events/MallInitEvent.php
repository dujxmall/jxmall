<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-19
 * Time: 16:11
 */

namespace app\events;


use yii\base\Event;

class MallInitEvent extends Event
{
    const MALL_INIT = 'mall_init';
    public $mall_id;
}