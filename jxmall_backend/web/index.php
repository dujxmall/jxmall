<?php
defined('IN_IA') or define('IN_IA', true);
error_reporting(E_ERROR | E_PARSE);
require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config/web.php';
(new \app\core\WebApplication($config))->run();
