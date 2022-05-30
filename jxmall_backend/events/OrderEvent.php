<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-14
 * Time: 17:57
 */

namespace app\events;


use yii\base\Event;

class OrderEvent extends Event
{
    const STATUS_CHANGE = 'status_change';
    const ORDER_CREATED = 'order_created';
    const ORDER_CANCEL = 'order_cancel';
    public $order_id;
}