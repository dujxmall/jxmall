<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-13
 * Time: 22:54
 */

namespace app\forms\api\login;


use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\ConstantHelper;
use app\helpers\JobHelper;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\helpers\WechatHelper;
use app\jobs\user\BindParentJob;
use app\models\BaseModel;
use app\models\User;
use app\models\UserInfo;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\MiniProgram\Application;
use jianyan\easywechat\WechatUser;

class LoginForm extends BaseModel
{
    public $code;
    public $encryptedData;
    public $iv;
    public $access_token;
    public $openId;
    public $unionId;
    public $avatarUrl;
    public $nickName;
    public $mobile;
    public $password;
    private $mpwx_try_times = 0;

    public function rules()
    {
        return [
            [['code', 'iv', 'encryptedData', 'openId', 'unionId', 'nickName', 'avatarUrl', 'mobile', 'password'], 'string']
        ]; // TODO: Change the autogenerated stub
    }

    public function loginByApp()
    {
        if (!$this->unionId || !$this->nickName || !$this->avatarUrl || !$this->openId) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '用户信息不完整！');
        }
        $is_subscribe = 0;

        $user = User::findOne(['openid' => $this->openId, 'mall_id' => $this->mall_id]);
        if (!$user) {
            $user_info = UserInfo::findOne(['openid' => $this->openId, 'mall_id' => $this->mall_id]);
            $user = null;
            if (!$user_info) {
                if ($this->unionId) {
                    $user = User::findOne(['union_id' => $this->unionId, 'mall_id' => $this->mall_id, 'is_delete' => 0]);
                }
                $t = \Yii::$app->db->beginTransaction();
                if (!$user) {
                    $user = new User();
                    $user->platform = User::PLATFORM_WXAPP;
                    $user->mall_id = \Yii::$app->mall->id;
                }
                if ($this->unionId) {
                    $user->union_id = $this->unionId;
                }
                $user->current_platform = ConstantHelper::PLATFORM_APP;
                $user->openid = $this->openId;
                $user->avatar_url = $this->avatarUrl;
                $user->nickname = $this->nickName;
                $user->is_subscribe = $is_subscribe;
                $user->login_ip = $_SERVER["REMOTE_ADDR"];
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                if (!$user->save()) {
                    $t->rollBack();
                    return $this->responseErrorMsg($user);
                }
                $user_info = new UserInfo();
                $user_info->mall_id = \Yii::$app->mall->id;
                $user_info->user_id = $user->id;
                $user_info->platform = UserInfo::PLATFORM_WXAPP;
                $user_info->openid = $this->openId;
                if (!$user_info->save()) {
                    \Yii::warning($user_info->getFirstErrors());
                    $t->rollBack();
                    return $this->responseErrorMsg($user_info);
                }
                $t->commit();
            } else {
                $user = User::findOne(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id, 'id' => $user_info->user_id]);
                if (!$user) {
                    return $this->apiResponse(ApiCode::CODE_FAIL, '登录错误');
                }
                if ($this->unionId) {
                    $user_info->union_id = $this->unionId;
                    $user_info->save();
                    $user->union_id = $this->unionId;
                }
                $user->current_platform = ConstantHelper::PLATFORM_APP;
                $user->openid = $this->openId;
                $user->avatar_url = $this->avatarUrl;
                $user->nickname = $this->nickName;
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                $user->login_ip = \Yii::$app->request->userIP;
                $user->is_subscribe = $is_subscribe;
                if (!$user->save()) {
                    return $this->responseErrorMsg($user_info);
                }
            }
        } else {
            $user->current_platform = ConstantHelper::PLATFORM_APP;
            $user->avatar_url = $this->avatarUrl;
            $user->nickname = $this->nickName;
            $user->login_ip = $_SERVER["REMOTE_ADDR"];
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->auth_key = \Yii::$app->security->generateRandomString();
            if ($this->unionId && !$user->union_id) {
                $user->union_id = $this->unionId;
            }
            if (!$user->save()) {
                return $this->responseErrorMsg($user);
            }
        }
        if ($user) {
            $level = OptionHelper::get('level_' . $user->level_id, \Yii::$app->mall->id);
            $userData = $user->attributes;
            $userData['level_name'] = $level ? $level['name'] : '默认等级';
            return $this->apiResponse(ApiCode::CODE_SUCCESS, '登录成功', ['access_token' => $user->access_token, 'user' => ['id' => $user->id, 'openid' => $this->openId, 'nickname' => $user->nickname, 'avatar_url' => $user->avatar_url, 'level_name' => $level ? $level['name'] : '默认等级']]);
        }
        return $this->apiResponse(ApiCode::CODE_FAIL, '登录失败,请重试');
    }

    public function getWechatUserInfo()
    {
        $app = \Yii::$app->wechat->app;
        try {
            $user = $app->oauth->userFromCode($this->code);
            if ($user) {
                $info = $user->getRaw();
                $info['avatarUrl'] = $user->getAvatar();
                $info['nickName'] = $user->getNickname();
            } else {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '获取微信信息失败!');
            }
            $user_detail = $app->user->get($info['openid']);
            if ($user_detail && $user_detail['subscribe']) {
                $is_subscribe = 1;
            }
        } catch (\Exception $exception) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, $exception->getMessage());
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['info' => $info]);
    }

    public function loginByWechat()
    {
        $app = \Yii::$app->wechat->app;
        $wechatUser = $app->oauth->userFromCode($this->code);
        $is_subscribe = 0;
        if (!empty($wechatUser)) {
            $info = $wechatUser->getRaw();
            if (empty($info)) {
                return ResponseHelper::send(ApiCode::CODE_FAIL, '未正确获取到登录信息!');
            }
            $user_detail = $app->user->get($info['openid']);
            if ($user_detail && $user_detail['subscribe']) {
                $is_subscribe = 1;
            }
            $unionid = $info['unionid'];
            $openid = $info['openid'];
            $user = User::findOne(['openid' => $openid, 'mall_id' => $this->mall_id]);
            if (!$user) {
                $user_info = UserInfo::findOne(['openid' => $info['openid'], 'mall_id' => $this->mall_id]);
                if (!$user_info) {
                    if ($unionid) {
                        $user = User::findOne(['union_id' => $unionid, 'is_delete' => 0]);
                    }
                    $t = \Yii::$app->db->beginTransaction();
                    if (!$user) {
                        $user = new User();
                        $user->platform = User::PLATFORM_WECHAT;
                        $user->mall_id = \Yii::$app->mall->id;
                    }
                    if ($unionid) {
                        $user->union_id = $unionid;
                    }
                    $user->current_platform = ConstantHelper::PLATFORM_H5;
                    $user->openid = $openid;
                    $user->is_subscribe = $is_subscribe;
                    $user->avatar_url = $wechatUser->getAvatar();
                    $user->nickname = $wechatUser->getNickname();
                    $user->login_ip = $_SERVER["REMOTE_ADDR"];
                    $user->access_token = \Yii::$app->security->generateRandomString();
                    $user->auth_key = \Yii::$app->security->generateRandomString();
                    if (!$user->save()) {
                        $t->rollBack();
                        return $this->responseErrorMsg($user);
                    }

                    $user_info = new UserInfo();
                    $user_info->mall_id = $this->mall_id;
                    $user_info->user_id = $user->id;
                    $user_info->platform = UserInfo::PLATFORM_WECHAT;
                    $user_info->openid = $openid;
                    if ($unionid) {
                        $user_info->union_id = $unionid;
                    }
                    if (!$user_info->save()) {
                        $t->rollBack();
                        return $this->responseErrorMsg($user_info);
                    }
                    $t->commit();
                } else {
                    $user = User::findOne(['id' => $user_info->user_id, 'is_delete' => 0]);
                    if (!$user) {
                        return ResponseHelper::send(ApiCode::CODE_FAIL, '用户数据错误！');
                    }
                    $user->current_platform = ConstantHelper::PLATFORM_H5;
                    $user->openid = $openid;
                    $user->avatar_url = $wechatUser->getAvatar();
                    $user->nickname = $wechatUser->getNickname();
                    $user->is_subscribe = $is_subscribe;
                    $user->login_ip = $_SERVER["REMOTE_ADDR"];
                    $user->access_token = \Yii::$app->security->generateRandomString();
                    $user->auth_key = \Yii::$app->security->generateRandomString();
                    if ($unionid) {
                        $user->union_id = $unionid;
                        $user_info->union_id = $unionid;
                        $user_info->save();
                    }
                    if (!$user->save()) {
                        return $this->responseErrorMsg($user_info);
                    }
                }

            } else {
                $user->current_platform = ConstantHelper::PLATFORM_H5;
                $user->avatar_url = $this->avatarUrl;
                $user->nickname = $this->nickName;
                $user->login_ip = $_SERVER["REMOTE_ADDR"];
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                if ($unionid && !$user->union_id) {
                    $user->union_id = $unionid;
                }
                if (!$user->save()) {
                    return $this->responseErrorMsg($user);
                }
            }
            if ($user) {
                $headers = \Yii::$app->request->headers;
                if ($headers && $headers['x-parent-id'] && $headers['x-parent-id'] != -1) {
                    if (!$user->parent_id || $user->parent_id == 0) { //没有上级 插入绑定队列
                        JobHelper::push(new BindParentJob(['user_id' => $user->id, 'parent_id' => $headers['x-parent-id']]), 0);
                    }
                }
                $level = OptionHelper::get('level_' . $user->level_id, \Yii::$app->mall->id);
                $userData = $user->attributes;
                $userData['level_name'] = $level ? $level['name'] : '默认等级';
                return $this->apiResponse(ApiCode::CODE_SUCCESS, '登录成功', ['access_token' => $user->access_token, 'openid' => $openid, 'user' => ['id' => $user->id, 'nickname' => $user->nickname, 'avatar_url' => $user->avatar_url, 'level_name' => $level ? $level['name'] : '默认等级']]);
            }
        }
        return $this->apiResponse(ApiCode::CODE_FAIL, '登录失败,请重试');
    }


    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/26
     * Time：11:36
     * Note：
     * @param Application $app
     */
    private function mpwxDecrypt($app)
    {
        if ($this->mpwx_try_times >= 5) {
            return null;
        }
        \Yii::warning('重新解密');
        $this->mpwx_try_times += 1;
        try {
            $session = $app->auth->session($this->code);
        } catch (InvalidConfigException $e) {
            return $this->apiResponse(ApiCode::CODE_FAIL, $e->getMessage());
        }
        $openid = $session['openid'];//用户的openID
        $sessionKey = $session['session_key'];//session_key
        $unionid = '';
        if (isset($session['unionid'])) {
            $unionid = $session['unionid'];//
        }
        $result = null;
        \Yii::warning($session);
        try {
            if ($session && isset($session['errcode']) && ($session['errcode'] == 40029 || $session['errcode'] == 40163)) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '登录失败，请重试');
            }
            $result = $app->encryptor->decryptData($sessionKey, $this->iv, $this->encryptedData);
        } catch (\Exception $e) {

        }

        if (empty($result)) {
            $this->mpwxDecrypt($app);
            return;
        }

        \Yii::warning($result);
        return array_merge($result, ['unionid' => $unionid, 'openid' => $openid]);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-28
     * @Time: 21:14
     * @Note:小程序授权登录
     * @return array|string
     * @throws \yii\base\Exception
     */
    public function loginByMpwx()
    {

        $app = \Yii::$app->wechat->miniProgram;
        if (!$app) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '小程序配置失败');
        }
        try {
            $session = $app->auth->session($this->code);
        } catch (InvalidConfigException $e) {
            return $this->apiResponse(ApiCode::CODE_FAIL, $e->getMessage());
        }
        $openid = $session['openid'];//用户的openID
        $unionid = '';
        if (isset($session['unionid'])) {
            $unionid = $session['unionid'];//
        }

        if (isset($session['errcode']) && in_array($session['errcode'], [40029])) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, $session['errmsg']);
        }
        \Yii::warning($session);
        $user = User::findOne(['openid' => $openid, 'mall_id' => $this->mall_id]);
        if (!$user) {
            $user_info = UserInfo::findOne(['openid' => $openid, 'mall_id' => $this->mall_id, 'platform' => UserInfo::PLATFORM_MPWX, 'is_delete' => 0]);
            if (!$user_info) {
                if ($unionid) {
                    $user = User::findOne(['union_id' => $unionid, 'is_delete' => 0]);
                }
                $t = \Yii::$app->db->beginTransaction();
                if (!$user) {
                    $user = new User();
                    $user->platform = User::PLATFORM_MPWX;
                    $user->mall_id = \Yii::$app->mall->id;
                }
                if ($unionid) {
                    $user->union_id = $unionid;
                }
                $user->current_platform = ConstantHelper::PLATFORM_MPWX;
                $user->openid = $openid;
                $user->avatar_url = $this->avatarUrl;
                $user->nickname = $this->nickName;
                $user->login_ip = $_SERVER["REMOTE_ADDR"];
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                if (!$user->save()) {
                    $t->rollBack();
                    return $this->responseErrorMsg($user);
                }
                $user_info = new UserInfo();
                $user_info->mall_id = \Yii::$app->mall->id;
                $user_info->user_id = $user->id;
                $user_info->platform = UserInfo::PLATFORM_MPWX;
                $user_info->openid = $openid;
                $user_info->union_id = $unionid;
                if (!$user_info->save()) {
                    $t->rollBack();
                    return $this->responseErrorMsg($user_info);
                }
                $t->commit();
            } else {
                $user = User::findOne(['id' => $user_info->user_id, 'is_delete' => 0]);
                if (!$user) {
                    return ResponseHelper::send(ApiCode::CODE_FAIL, '用户数据错误！');
                }
                $user->current_platform = ConstantHelper::PLATFORM_MPWX;
                $user->openid = $openid;
                $user->avatar_url = $this->avatarUrl;
                $user->nickname = $this->nickName;
                $user->login_ip = $_SERVER["REMOTE_ADDR"];
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                if ($unionid) {
                    $user->union_id = $unionid;
                    $user_info->union_id = $unionid;
                    $user_info->save();
                }
                if (!$user->save()) {
                    return $this->responseErrorMsg($user);
                }
            }
        } else {
            $user->current_platform = ConstantHelper::PLATFORM_MPWX;
            $user->avatar_url = $this->avatarUrl;
            $user->nickname = $this->nickName;
            $user->login_ip = $_SERVER["REMOTE_ADDR"];
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->auth_key = \Yii::$app->security->generateRandomString();
            if ($unionid && !$user->union_id) {
                $user->union_id = $unionid;
            }
            if (!$user->save()) {
                return $this->responseErrorMsg($user);
            }
        }
        if ($user) {
            $headers = \Yii::$app->request->headers;
            if ($headers && $headers['x-parent-id'] && $headers['x-parent-id'] != -1) {
                if (!$user->parent_id || $user->parent_id == 0) { //没有上级 插入绑定队列
                    JobHelper::push(new BindParentJob(['user_id' => $user->id, 'parent_id' => $headers['x-parent-id']]));
                }
            }
            $level = OptionHelper::get('level_' . $user->level_id, \Yii::$app->mall->id);
            $userData = $user->attributes;
            $userData['level_name'] = $level ? $level['name'] : '默认等级';
            return $this->apiResponse(ApiCode::CODE_SUCCESS, '登录成功', ['access_token' => $user->access_token, 'user' => ['id' => $user->id, 'openid' => $user_info->openid, 'nickname' => $user->nickname, 'avatar_url' => $user->avatar_url, 'level_name' => $level ? $level['name'] : '默认等级']]);
        }

        return $this->apiResponse(ApiCode::CODE_FAIL, '登录失败,请重试');
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2022-03-24
     * @Time: 19:48
     * @Note:手机号登录
     */
    public function loginByMobile()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        if (!$this->openId) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '登录参数有误！');
        }
        if (!$this->mobile || !$this->password) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '账号或密码不正确');
        }
        $user = User::findOne(['mobile' => $this->mobile, 'mall_id' => $this->mall_id, 'is_delete' => 0]);
        if (!$user) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '账号或密码不正确');
        }
        if (!\Yii::$app->security->validatePassword($this->password, $user->password)) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '账号或密码不正确');
        }
        $user->platform = User::PLATFORM_MOBILE;
        $user->openid = $this->openId;
        $user->current_platform = \Yii::$app->platform;
        $user->access_token = \Yii::$app->security->generateRandomString();
        $user->auth_key = \Yii::$app->security->generateRandomString();
        if (\Yii::$app->wechat->getIsWechat()) {
            $user_detail = \Yii::$app->wechat->app->user->get($user->openid);
            if ($user_detail && $user_detail['subscribe']) {
                $user->is_subscribe = 1;
            }
        }

        if (!$user->save()) {
            return $this->responseErrorMsg($user);
        }
        $headers = \Yii::$app->request->headers;
        if ($headers && $headers['x-parent-id'] && $headers['x-parent-id'] != -1) {
            if (!$user->parent_id || $user->parent_id == 0) { //没有上级 插入绑定队列
                JobHelper::push(new BindParentJob(['user_id' => $user->id, 'parent_id' => $headers['x-parent-id']]));
            }
        }
        $level = OptionHelper::get('level_' . $user->level_id, \Yii::$app->mall->id);
        $userData = $user->attributes;
        $userData['level_name'] = $level ? $level['name'] : '默认等级';
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '登录成功', ['access_token' => $user->access_token, 'user' => ['id' => $user->id, 'openid' => $user->openid, 'nickname' => $user->nickname, 'avatar_url' => $user->avatar_url, 'level_name' => $level ? $level['name'] : '默认等级']]);
    }
}
