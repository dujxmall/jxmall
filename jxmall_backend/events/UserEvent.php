<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-30
 * Time: 15:13
 */

namespace app\events;


use yii\base\Event;

class UserEvent extends Event
{

    const LOGIN = 'login';
    const CHECK_UPGRADE = "check_upgrade";
    const PARENT_CHANGE = "parent_change";
    public $user_id;

}