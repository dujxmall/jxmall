<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:10
 */

namespace app\controllers;

use app\helpers\WechatHelper;
use app\models\Mall;
use yii\web\Request;
use yii\web\Response;

/**
 * Class BaseController
 * @package app\controllers
 * @Notes
 * @property Request $request
 */
class BaseController extends \yii\web\Controller
{
    public $request;
    public $mall_id;

    public function init()
    {
        file_get_contents("php://input");
        \Yii::$app->admin->enableSession = false;
        \Yii::$app->admin->enableAutoLogin = false;
        \Yii::$app->isAdmin=true;
        $this->request = \Yii::$app->request;
        $headers = $this->request->getHeaders();
        $mall_id = $headers['x-mall-id'];
        if (!$mall_id) {
            $mall_id = \Yii::$app->request->get('mall_id');
        }
        if (!$mall_id) {
            //微信支付回调返回商城id
            \Yii::$app->response->format = Response::FORMAT_XML;
            $xml = \Yii::$app->request->rawBody;
            $res = WechatHelper::xmlToArray($xml);
            if (isset($res["attach"])) {
                $mall_id = $res["attach"];
            }
        }
        if ($mall_id) {
            $mall = Mall::getOne($mall_id);
            if ($mall) {
                \Yii::$app->mall = $mall;
            }
            $this->mall_id = $mall_id;
        }

        parent::init(); // TODO: Change the autogenerated stub
    }
}