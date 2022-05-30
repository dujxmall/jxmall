<?php

return [
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'password' => '20sx1314',
        'database'=>4
    ],
    'queue' => [
        'class' => \yii\queue\redis\Queue::class,
        'channel' => 'jxmall_sinbel_cn',
    ],
];
