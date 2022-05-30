<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-09
 * Time: 17:23
 */

namespace app\controllers\admin;

use app\controllers\BaseController;
use app\core\ApiCode;
use app\forms\admin\LoginForm;
use app\forms\admin\RegisterForm;
use app\helpers\LoginHelper;
use app\helpers\ResponseHelper;
use app\helpers\SerializeHelper;
use app\models\Admin;
use app\models\Mall;


class PassportController extends BaseController
{

    public $enableCsrfValidation = false;

    public function actionLogin()
    {
        if ($this->request->isPost) {
            $form = new LoginForm();
            $form->attributes = $this->request->post();
            return $this->asJson($form->login());
        } else {
            return $this->asJson(['code' => ApiCode::CODE_FAIL, 'msg' => '用户名或密码不正确！']);
        }
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2021-05-05
     * @Time: 22:38
     * @Note:微擎登录
     */
    public function actionWe7Login()
    {

        $session = \Yii::$app->session;
        $we7_user = $session->get('we7_user');
        $we7_account = $session->get('we7_account');


        if (empty($we7_user) || empty($we7_account)) { //都跳到登录
            $current_url = \Yii::$app->request->absoluteUrl;
            $key = 'addons/' . WE7_MODULE_NAME;
            $we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
            $we7_url = $we7_url . "addons/" . WE7_MODULE_NAME . '/admin';
            $this->redirect($we7_url)->send();
            exit;
        }

        $we7_user = SerializeHelper::decode($we7_user);
        $we7_account = SerializeHelper::decode($we7_account);


        $admin = Admin::findOne([
            'we7_uid' => $we7_user['uid'],
        ]);
        if (!$admin) {
            $admin = Admin::findOne([
                'username' => $we7_user['username'],
            ]);
        }
        if (!$admin) {
            $admin = new Admin();
            $admin->username = $we7_user['username'];
            $admin->password = \Yii::$app->security->generatePasswordHash(md5(uniqid()));
            $admin->access_token = \Yii::$app->security->generateRandomString();
            $admin->auth_key = \Yii::$app->security->generateRandomString();
            $admin->we7_uid = $we7_user['uid'];
            $admin->save();
            if (!$admin->save()) {


            }
        } elseif (!$admin->we7_uid || $admin->we7_uid != $we7_user['uid']) {
            $admin->we7_uid = $we7_user['uid'];
            $admin->save();
        }
        \Yii::$app->admin->login($admin); //管理员登录
        $mall = Mall::findOne([
            'uniacid' => $we7_account['uniacid'],
        ]);
        if (!$mall) {
            $mall = new Mall();
            $mall->uniacid = $we7_account['uniacid'];
            $mall->name = $we7_account['name'];
            $mall->admin_id = $admin->id;
            if (!$mall->save()) {

                exit;
            }
        }
        \Yii::$app->mall = $mall;


        $current_url = \Yii::$app->request->absoluteUrl;
        $key = 'addons/' . WE7_MODULE_NAME;
        $we7_url = mb_substr($current_url, 0, stripos($current_url, $key));
        $we7_url = $we7_url . "addons/" . WE7_MODULE_NAME . '/admin/#/mall/data';


        \Yii::$app->response->redirect($we7_url)->send();
        exit;


        return $this->asJson(ResponseHelper::send(ApiCode::CODE_FAIL, '没有数据'));
    }


    public function actionRegister()
    {
        $form = new RegisterForm();
        $form->attributes = $this->request->post();
        return $this->asJson($form->save());
    }

    public function actionCaptcha()
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randStr = str_shuffle($str);//打乱字符串
        $rands = substr($randStr, 0, 4);//substr(string,start,length);返回字符串的一部分
        LoginHelper::setAuthCode($rands);
        return $this->asJson(['code' => ApiCode::CODE_SUCCESS, 'msg' => '', 'data' => ['captcha' => $rands]]);
    }
}
