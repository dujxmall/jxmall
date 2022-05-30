<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:29
 */

namespace app\core;


/**
 * Class WebApplication
 * @package app\core
 * @Notes 核心的webapplication
 */
class WebApplication extends \yii\web\Application
{
    use Application;

    public function __construct(array $config = [])
    {
        // $this->loadDotEnv();
       require __DIR__ . '/../Yii.php';
        //  require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
        parent::__construct($config);
        $this->responseAsJson();
        $this->registerListeners();
    }

}
