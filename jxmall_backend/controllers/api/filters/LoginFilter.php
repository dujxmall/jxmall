<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-17
 * Time: 15:57
 */

namespace app\controllers\api\filters;

use app\core\ApiCode;
use app\events\UserEvent;
use app\helpers\CacheHelper;
use app\helpers\JobHelper;
use app\helpers\LockHelper;
use app\jobs\user\BindParentJob;
use app\jobs\user\CheckInviterJob;
use yii\base\ActionFilter;

class LoginFilter extends ActionFilter
{
    public $ignore;
    public $only;


    public function beforeAction($action)
    {
        $headers = \Yii::$app->request->headers;
        if ($headers['x-mall-id']) {
            if (!\Yii::$app->mall) {
                \Yii::$app->response->data = [
                    'code' => ApiCode::CODE_MALL_NOT_EXIST,
                    'msg' => '商城不存在!',
                ];
                return false;
            }
        } else {
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_MALL_NOT_EXIST,
                'msg' => '商城不存在!',
            ];
            return false;
        }
        if (\Yii::$app->user->isGuest) {
            if ($headers && $headers['x-access-token']) {
                \Yii::$app->user->loginByAccessToken($headers['x-access-token']);
            }
        }
        $id = $action->id;
        if (is_array($this->ignore) && in_array($id, $this->ignore)) {
            return true;
        }
        if (is_array($this->only) && !in_array($id, $this->only)) {
            return true;
        }
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_NOT_LOGIN,
                'msg' => '请先登录!',
            ];
            return false;
        }

        $user_id = \Yii::$app->user->identity->id;

        if (LockHelper::setUserLogin($user_id)) {
            $event = new UserEvent();
            $event->user_id = $user_id;
            if ($headers && $headers['x-parent-id'] && $headers['x-parent-id'] != -1) {
                if (\Yii::$app->user->identity->parent_id == 0) { //没有上级 插入绑定队列
                    JobHelper::push(new BindParentJob(['user_id' => $user_id, 'parent_id' => $headers['x-parent-id']]));
                }
            }
            if (!\Yii::$app->user->identity->is_inviter) {
                JobHelper::push(new CheckInviterJob(['user_id' => $user_id]));
            }
            \Yii::$app->trigger(UserEvent::LOGIN, $event);
            \Yii::$app->trigger(UserEvent::CHECK_UPGRADE, $event);
        }
        return parent::beforeAction($action);
    }
}