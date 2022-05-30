<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/10
 * Time: 13:20
 * Note:商家小票
 */

use app\core\WebApplication;

$_GET['r'] = 'wechat/ticket/index';

error_reporting(E_ALL);

// 注册 Composer 自动加载器
require(__DIR__ . '/../../vendor/autoload.php');
$config = require __DIR__ . '/../../config/web.php';
// 创建、运行一个应用
$application = new WebApplication($config);
$application->run();
