<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-22
 * Time: 14:51
 */

namespace app\core;


class ConsoleApplication extends \yii\console\Application
{
    use Application;

    public function __construct($config=null)
    {
       // $this->loadDotEnv();
        require __DIR__ . '/../Yii.php';
        //  require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
        if (!$config) {
            $config = require __DIR__ . '/../config/console.php';
        }
        parent::__construct($config);
        $this->responseAsJson();
        $this->registerListeners();
    }

}
