<?php

namespace app\controllers\admin\filters;

use app\core\ApiCode;
use app\models\Admin;
use yii\base\ActionFilter;


class AdminFilter extends ActionFilter
{
    public $loginUrl;
    public $safeActions;
    public $onlyActions;
    public $safeRoutes;
    public $ignore;
    public $only;

    public function beforeAction($action)
    {
        $id = $action->id;
        if (is_array($this->ignore) && in_array($id, $this->ignore)) {
            return true;
        }
        if (is_array($this->only) && !in_array($id, $this->only)) {
            return true;
        }
        $headers = \Yii::$app->request->headers;
        if (\Yii::$app->admin->isGuest) {
            if ($headers && $headers['x-admin-token']) {
                 if (\Yii::$app->admin->loginByAccessToken($headers['x-admin-token'])) {
                     return true;
                 }
             }
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_NOT_LOGIN,
                'msg' => '请先登录!',
            ];
            return false;
        }
        return parent::beforeAction($action);
    }
}
