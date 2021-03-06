<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:51
 */

namespace app\controllers\api;

use app\controllers\BaseController;
use app\models\User;

class Controller extends BaseController
{
    public $enableCsrfValidation = false;
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        \Yii::$app->user->enableSession = true;
        \Yii::$app->user->enableAutoLogin = true;
        \Yii::$app->isAdmin = false;
        $headers = \Yii::$app->request->headers;
        \Yii::$app->platform = $headers['x-platform'];
        if ($headers && $headers['x-access-token']) {
            if (\Yii::$app->user->isGuest) {
                \Yii::$app->user->loginByAccessToken($headers['x-access-token']);
            }
        }
    }

}