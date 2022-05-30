<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:57
 */

namespace app\core;

/**
 * Class ApiCode
 * @package app\core
 * 系统状态码调用
 */
class ApiCode
{

    /**
     *  返回成功
     */
    const CODE_SUCCESS = 0;
    /**
     * 返回失败
     */
    const CODE_FAIL = 1;
    /**
     * 未登录
     *
     */
    const CODE_NOT_LOGIN = -1;


    /**
     * 商城不存在
     */
    const CODE_MALL_NOT_EXIST = -2;

    /**
     * 状态码：多商户未登录
     */
    const CODE_MCH_NOT_LOGIN = -3;



}