<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/5/26
 * Time: 13:04
 * Note:
 */

namespace app\events;


use yii\base\Event;

class WechatOrderEvent extends Event
{

    public $order_id;
}