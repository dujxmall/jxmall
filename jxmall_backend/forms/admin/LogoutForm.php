<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-11
 * Time: 19:30
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\models\Admin;
use app\models\BaseModel;

class LogoutForm extends BaseModel
{

    public function logout()
    {

        /**
         * @var Admin $admin ;
         */
        $admin = \Yii::$app->admin->identity;
        \Yii::$app->admin->logout();
        $admin->access_token = null;
        $admin->save();
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '退出登录');
    }

}