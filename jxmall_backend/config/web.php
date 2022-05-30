<?php

$config = require __DIR__ . '/common.php';

$config['components']['request'] = [
    'cookieValidationKey' => 'dfsf132sda1f32ds1af',
];

$config['components']['errorHandler'] = [
    'errorAction' => 'site/error',
];
$config['id'] = 'jxmall';
return $config;
