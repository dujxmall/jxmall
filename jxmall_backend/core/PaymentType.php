<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-30
 * Time: 19:16
 */

namespace app\core;


class PaymentType
{
    /**
     *  微信支付
     */
    const TYPE_WECHAT = 0;
    /**
     * 余额
     */
    const TYPE_BALANCE = 1;

    const TYPE_SYSTEM = 2;


}