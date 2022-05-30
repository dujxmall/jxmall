<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-22
 * Time: 14:20
 */

namespace app\events;


use yii\base\BaseObject;
use yii\base\Event;

class CommonOrderDetailEvent extends Event
{
    const INSERT = 'insert';//创建新纪录
    const VALID = 'valid';
    const INVALID = 'invalid';
    public $id;
    public $common_order_id;

}