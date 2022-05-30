<?php
require(__DIR__ . '/init.php');
$redis = file_exists(__DIR__ . '/redis.php') ? require(__DIR__ . '/redis.php') : [];
$params = require __DIR__ . '/params.php';
$db = file_exists(__DIR__ . '/db.php') ? require(__DIR__ . '/db.php') : [
    'host' => null,
    'port' => null,
    'dbname' => null,
    'username' => null,
    'password' => null,
    'tablePrefix' => null,
];
$mongodb = file_exists(__DIR__ . '/mongodb.php') ? require(__DIR__ . '/mongodb.php') : null;

$config = [
    'name' => '聚象商城',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'mongodb'=>[
            'class' => $mongodb['class'],
            'dsn' => "mongodb://{$mongodb['host']}:{$mongodb['port']}/{$db['dbname']}"
        ],
        'plugin' => [
            'class' => '\app\components\Plugin',
        ],
        'joinPay' => [
            'class' => '\app\components\JoinPay',
        ],
        'wechat' => [
            'class' => 'jianyan\easywechat\Wechat',
            'userOptions' => [],  // 用户身份类参数
            'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
            'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
            'rebinds' => [ // 自定义服务模块
                // 'cache' => 'common\components\Cache',
            ]
        ],
        'cache' => [
            'class' => isset($redis['redis']) ? 'yii\redis\Cache' : 'yii\caching\FileCache',
        ],
        'redis' => $redis['redis'],
        'admin' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => true,
        ],
        //前台登录标识
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        'queue' => [ //稳点一点
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
        ],
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@runtime/logs/errors/' . date('Y-m-d') . '.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'levels' => ['warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m-d') . '.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/info/' . date('Y-m-d') . '.log',
                ]
            ]
        ],

        'db' => [
            'class' => \app\core\Connection::class,
            'dsn' => 'mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'],
            'dbName' => $db['dbname'],
            'tablePrefix' => $db['tablePrefix'],
            'charset' => 'utf8mb4',
            'masterConfig' => [
                'username' => $db['username'],
                'password' => $db['password'],
                'charset' => 'utf8mb4',
                'tablePrefix' => $db['tablePrefix'],
                'attributes' => [// use a smaller connection timeout
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],
            /*'slaveConfig' => [
                'username' => 'jxmall_slave',
                'password' => '6AHKB2bcDPstm6ym.',
                'charset' => 'utf8mb4',
                'tablePrefix' => $db['tablePrefix'],
                'attributes' => [
                    // use a smaller connection timeout
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],*/
            'masters' => [
                ['dsn' => 'mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'],]
            ],
            /*'slaves' => [
                ['dsn' => 'mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . 'jxmall_slave',]
            ],*/

        ],

        'session' => [
            'class' => 'yii\mongodb\Session',
            'db' => 'mongodb',
            'sessionCollection' => 'jxmall_session',
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'params' => $params,

];




if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
        'generators' => [
            'migrik' => [
                'class' => \insolita\migrik\gii\StructureGenerator::class,
                'templates' => [
                    'custom' => '@app/gii/default_structure',
                ],
            ],
            'migrikdata' => [
                'class' => \insolita\migrik\gii\DataGenerator::class,
                'templates' => [
                    'custom' => '@app/gii/migrator_data',
                ],
            ],
        ],
    ];
    if (class_exists("\yii\mongodb\gii\model\Generator")) {
        $config['modules']['gii']['generators']['mongoDbModel'] = ['class' => "\yii\mongodb\gii\model\Generator"];
    }
}

return $config;
