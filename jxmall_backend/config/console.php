<?php
$config = require __DIR__ . '/common.php';
$config['controllerNamespace'] = 'app\commands';
$config['components']['request'] = [
    'class' => \app\core\ConsoleRequest::class,
];
$config['components']['response'] = [
    'class' => \app\core\ConsoleResponse::class,
];
$config['controllerMap'] = [
    'migrate' => [
        'class' => 'app\commands\MigrateController'
    ],
    'mongodb-migrate' => [
        'class' => 'yii\mongodb\console\controllers\MigrateController',
        'migrationPath' => 'mongo/migrations',
    ],

];
$config['id'] = 'jxmall_console_app';
return $config;
