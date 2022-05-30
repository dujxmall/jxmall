<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-30
 * Time: 20:30
 */

namespace app\events;


use yii\base\Event;

class CommonOrderEvent extends Event
{
    const COMMON_ORDER_PAY = 'common_order_pay';
    const COMMON_ORDER_VALID = 'common_order_valid';
    const COMMON_ORDER_CREATED = 'common_order_created';
    const COMMON_ORDER_INVALID = 'common_order_invalid';
    const COMMON_ORDER_STATUS_CHANGE = 'common_order_status_change';
    public $id;
}