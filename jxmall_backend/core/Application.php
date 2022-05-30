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


use app\components\Plugin;
use app\helpers\EnvHelper;
use app\listeners\BaseListener;
use app\listeners\ListenerRegister;
use app\models\Mall;
use Dotenv\Dotenv;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Trait Application
 * @package app\core
 * @property Response $response
 * @property Mall $mall
 * @property string $platform
 * @property  Plugin $plugin
 * @property boolean isAdmin
 */
trait Application
{

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 16:32
     * @Note:加载配置文件
     * @return $this
     */
    protected function loadDotEnv()
    {
        try {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__));
            $list = $dotenv->load();
            foreach ($list as $k => $v) {
                if ($v === 'true' || $v === 'false' || $v === true || $v === false) {
                    if ($v === 'true' || $v === true) {
                        define_once($k, true);
                    } else {
                        define_once($k, false);
                    }
                } else {
                    define_once($k, $v);
                }
            }

        } catch (\Dotenv\Exception\InvalidPathException $ex) {

        }
        return $this;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 16:31
     * @Note:接口数据返回格式
     * @return $this
     */
    protected function responseAsJson()
    {
        $this->response->on(
            Response::EVENT_BEFORE_SEND,
            function ($event) {
                /** @var \yii\web\Response $response */
                $response = $event->sender;
                if (is_array($response->data) || is_object($response->data)) {
                    $response->format = \yii\web\Response::FORMAT_JSON;
                }
            }
        );
        return $this;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-10
     * @Time: 17:05
     * @Note: 区别路由 加载插件
     * @param $route
     * @param array $params
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function runAction($route, $params = [])
    {
        bcscale(2);//配置BC函数小数精度

        $route = ltrim($route, '/');
        $pattern = '/^plugin\/.*/';
        preg_match($pattern, $route, $matches);
        if ($matches) {
            $originRoute = $matches[0];
            $originRouteArray = mb_split('/', $originRoute);
            $pluginName = !empty($originRouteArray[1]) ? $originRouteArray[1] : null;
            if (!$pluginName) {
                throw new NotFoundHttpException();
            }
            $plugin = $this->plugin->getPlugin($pluginName);
            if (!$plugin) {  //这里要检查这个插件有没有安装
                throw new NotFoundHttpException();
            }
            $controllerId = 'index';
            $controllerClass = "app\\plugins\\{$pluginName}\\controllers\\IndexController";
            $actionId = 'index';
            for ($i = 2; $i < count($originRouteArray); $i++) {
                $nameSpace = !empty($originRouteArray[$i]) ? $originRouteArray[$i] : 'index';
                $controllerId = !empty($originRouteArray[$i + 1]) ? $originRouteArray[$i + 1] : 'index';
                $controllerName = preg_replace_callback('/\-./', function ($e) {
                    return ucfirst(trim($e[0], '-'));
                }, $controllerId);
                $controllerName = ucfirst($controllerName);
                $controllerName .= 'Controller';
                $controllerClass = "app\\plugins\\{$pluginName}\\controllers\\{$nameSpace}\\{$controllerName}";
                $actionId = !empty($originRouteArray[$i + 2]) ? $originRouteArray[$i + 2] : 'index';
                if (class_exists($controllerClass)) {
                    break;
                }
            }
            if (!class_exists($controllerClass)) {
                throw new NotFoundHttpException(\Yii::t('yii', 'Page not found.'));
            }
            try {
                /** @var Controller $controller */
                $controller = \Yii::createObject($controllerClass, [$controllerId, $this]);
                $module = new Module($pluginName, $this);
                $controller->module = $module;
                $this->controller = $controller;
                \Yii::$app->plugin->setCurrentPlugin($plugin); //这里设置当前的插件
                return $controller->runAction($actionId, $params);
            } catch (\ReflectionException $e) {
                throw new NotFoundHttpException(\Yii::t('yii', 'Page not found.'), 0, $e);
            }
        }
        return parent::runAction($route, $params);
    }

    public function getMall()
    {
        return $this->mall;
    }

    public function setMall($mall)
    {
        $this->mall = $mall;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($flag = false)
    {
        $this->isAdmin = $flag;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }


    protected function registerListeners()
    {
        $register = new ListenerRegister();
        $listenerClasses = $register->getListeners();
        foreach ($listenerClasses as $listenerClass) {
            /**
             * @var BaseListener $listener
             */
            $listener = new $listenerClass();
            if ($listener instanceof BaseListener) {
                $listener->register();
            }
        }
        //注册插件事件
        try {
            \Yii::$app->plugin->registerListeners();
        } catch (\Exception $exception) {

        }
        return $this;
    }
}
