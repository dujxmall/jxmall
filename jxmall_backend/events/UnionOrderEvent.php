<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-30
 * Time: 18:34
 */

namespace app\events;


use yii\base\Event;

class UnionOrderEvent extends Event
{

    const UNION_ORDER_PAY='union_order_pay';
    public $union_order_id;

}