<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-09
 * Time: 17:58
 */

namespace app\forms\admin;

use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\LoginHelper;
use app\helpers\ResponseHelper;
use app\helpers\ServerHelper;
use app\models\Admin;
use app\models\AdminMall;
use app\models\Authority;
use app\models\BaseModel;
use app\models\Mall;
use app\plugins\mch\models\Mch;

class LoginForm extends BaseModel
{
    public $username;
    public $password;
    public $captcha;
    public $checked;
    public $login_type;
    public $sms_code;
    public $mobile;

    public function rules()
    {
        return [
            [['login_type'], 'default', 'value' => 0],
            [['username', 'password', 'mobile', 'sms_code'], 'string'],
            [['checked', 'login_type'], 'integer'],
            [['captcha'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }


    public function login()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }

        if ($this->login_type == 1) {
            if (!$this->mobile) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '请输入手机号');

            }
            if (!$this->sms_code) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '请输入验证码');
            }
            $code = CacheHelper::get('sys_auth_code_' . $this->mobile);
            if (!$code || $code != $this->sms_code) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '验证码不正确');
            }
            CacheHelper::remove('sys_auth_code_' . $this->mobile);
            $admin = Admin::findOne(['mobile' => $this->mobile, 'is_delete' => 0]);
        } else {
            if (!$this->password || !$this->username) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '用户名或密码不正确！');
            }
            $auth_code = LoginHelper::getAuthCode();
            if ($this->captcha) {
                $this->captcha = strtolower($this->captcha);
            }
            if (!$auth_code) {
                return ['code' => ApiCode::CODE_FAIL, 'msg' => '无法获取到验证码,登录失败！'];
            }
            $auth_code = strtolower($auth_code);
            if ($auth_code != $this->captcha) {
                return ['code' => ApiCode::CODE_FAIL, 'msg' => '验证码不正确！'];
            }
            $admin = Admin::findOne(['username' => $this->username, 'is_delete' => 0]);
            if (!$admin) {
                return ['code' => 1, 'msg' => '用户名或密码不正确'];
            }

            if ($this->login_type == 0) {
                if (!\Yii::$app->security->validatePassword($this->password, $admin->password)) {
                    return ['code' => 1, 'msg' => '用户名或密码不正确'];
                }
            }
        }
        if ($admin->is_expire) {
            return ['code' => 1, 'msg' => '账户已过期，请联系管理员'];
        }
        $headers = \Yii::$app->request->headers;
        if ($admin->mch_id) {
            if ($headers && !$headers['is-mch']) {
                return ['code' => 1, 'msg' => '登录失败，您不是管理员！'];
            }
        } else {
            if ($headers && $headers['is-mch']) {
                return ['code' => 1, 'msg' => '登录失败，您不是商户！'];
            }
        }
        $mall_id = 1;
        if ($admin->admin_type != Admin::ADMIN_TYPE_SUPER) {//不是超级管理员
            $adminMall = AdminMall::findOne(['admin_id' => $admin->id, 'is_delete' => 0]);
            if (!$adminMall) {
                if ($admin->admin_type == Admin::ADMIN_TYPE_FOUNDER) {//子账户
                    $mall = new Mall();
                    $mall->admin_id = $admin->id;
                    $mall->name = $admin->name . '的商城';
                    if (!$mall->save()) {
                        return $this->responseErrorMsg($mall);
                    }
                    $adminMall = new AdminMall();
                    $adminMall->admin_id = $admin->id;
                    $adminMall->mall_id = $mall->id;
                    $adminMall->role = AdminMall::FOUNDER;
                    $adminMall->save();
                } else {
                    return ResponseHelper::send(ApiCode::CODE_FAIL, '此账户未关联商城');
                }
            }
            $mall_id = $adminMall->mall_id;
        }
        $admin->login_ip = \Yii::$app->request->userIP;
        $admin->access_token = \Yii::$app->security->generateRandomString();
        $admin->auth_key = \Yii::$app->security->generateRandomString();
        $admin->save();
        \Yii::$app->admin->login($admin);

        $data = [
            'mall_id' => $mall_id,
            "token" => $admin->access_token,
            "expiresAt" => (time() + 7 * 24 * 60 * 60) * 1000
        ];
        return ['code' => 0, 'msg' => '登录成功', 'data' => $data];
    }
}
