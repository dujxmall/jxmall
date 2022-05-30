<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-25
 * Time: 22:31
 */

namespace app\helpers;

class InstallHelper
{
    const API_REGISTER = 'http://v.dujxmall.com/web/index.php?r=api/auth/register';
    const API_SMS_CODE = 'http://v.dujxmall.com/web/index.php?r=api/auth/sms-code';
    const API_MALL_INSTALL = 'http://v.dujxmall.com/web/index.php?r=api/version/mall-install';
}