<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 20:19
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\models\Admin;
use app\models\BaseModel;
use app\models\Mall;

class MallDeleteForm extends BaseModel
{
    public $id;
    public function rules()
    {
        return [
            [['id'], 'required'],

        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorMsg();
        }
        /**
         * @var Admin $admin
         */
        $admin = \Yii::$app->admin->identity;
        if (!$admin) {
            return $this->apiResponse(ApiCode::CODE_NOT_LOGIN, '请先登录');
        }
        if ($admin->admin_type == Admin::ADMIN_TYPE_OPERATOR) {//这个
            return $this->apiResponse(ApiCode::CODE_FAIL, '权限不足');
        }
        $mall = Mall::findOne(['id' => $this->id, 'is_delete' => 0]);
        if (!$mall) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '商城不存在或已被删除');
        }
        if ($admin->admin_type == Admin::ADMIN_TYPE_FOUNDER) {//这个
            if ($admin->id != $mall->admin_id) {
                return $this->apiResponse(ApiCode::CODE_FAIL, '权限不足');
            }
        }
        $mall->is_delete = 1;
        if (!$mall->save()) {
            return $this->responseErrorMsg($admin);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '删除成功');
    }

}