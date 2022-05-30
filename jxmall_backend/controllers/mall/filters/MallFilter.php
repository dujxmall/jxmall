<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 21:49
 */

namespace app\controllers\mall\filters;


use app\core\ApiCode;
use app\models\Admin;
use app\models\Mall;
use yii\base\ActionFilter;

class MallFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        /**
         * 微擎开发的时候使用的，
         * 微擎开发：先给他个默认的admin 账户
         */
        $headers = \Yii::$app->request->headers;
        $mall_id = \Yii::$app->session->get('mall_id');
        if (!$mall_id) {
            $mall_id = $headers['x-mall-id'];
        }
        if ($mall_id) {
            \Yii::$app->session->set('mall_id', $mall_id);
            if (!\Yii::$app->mall) {
                $mall = Mall::findOne($mall_id);
                if ($mall) {
                    \Yii::$app->mall = $mall;
                } else {
                    \Yii::$app->response->data = [
                        'code' => ApiCode::CODE_MALL_NOT_EXIST,
                        'msg' => '商城不存在!',
                    ];
                    return false;
                }
            }
        } else {
            $current_url = \Yii::$app->request->absoluteUrl;
            if (IS_WE7_APP) {
                $key = 'addons/' . WE7_MODULE_NAME . '/web/';
                $we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
                \Yii::$app->response->data = [
                    'code' => ApiCode::CODE_MALL_NOT_EXIST,
                    'msg' => '商城不存在!',
                    'data' => ['redirect_url' => $we7_url]
                ];
            } else {
                \Yii::$app->response->data = [
                    'code' => ApiCode::CODE_MALL_NOT_EXIST,
                    'msg' => '商城不存在!',
                    'data' => ['redirect_url' => $current_url]
                ];
            }
            return false;
        }

        if (\Yii::$app->admin->isGuest) {
            $access_token = $headers['x-admin-token'];
            if ($access_token) {
                if (\Yii::$app->admin->loginByAccessToken($access_token)) {
                    return parent::beforeAction($action);
                } else {
                    \Yii::warning('未登录...');
                }
            }
            $current_url = \Yii::$app->request->absoluteUrl;
            if (IS_WE7_APP) {
                $key = 'addons/' . WE7_MODULE_NAME . '/web/';
                $we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
                \Yii::$app->response->data = [
                    'code' => ApiCode::CODE_NOT_LOGIN,
                    'msg' => '未登录!',
                    'data' => ['redirect_url' => $we7_url]
                ];
            } else {
                \Yii::$app->response->data = [
                    'code' => ApiCode::CODE_NOT_LOGIN,
                    'msg' => '未登录!',
                    'data' => ['redirect_url' => $current_url]
                ];
            }
            return false;
        }
        return parent::beforeAction($action);
    }
}
