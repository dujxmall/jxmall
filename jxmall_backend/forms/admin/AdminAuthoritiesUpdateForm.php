<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/4
 * Time: 16:20
 * Note:
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\Admin;
use app\models\AdminAuthority;
use app\models\BaseModel;

class AdminAuthoritiesUpdateForm extends BaseModel
{
    public $id;
    public $authorityIds;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['authorityIds'], 'safe']
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $admin = Admin::getOne($this->id);
        if (!$admin) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '用户不存在！');
        }
        if (!$this->authorityIds || !is_array($this->authorityIds) || !count($this->authorityIds)) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '请选择权限');
        }
        $admin->authority_id = $this->authorityIds[0];
        if (!$admin->save()) {
            return $this->responseErrorMsg($admin);
        }
        //更新权限

        AdminAuthority::deleteAll(['admin_id' => $admin->id]);
        foreach ($this->authorityIds as $id) {
            $model = new AdminAuthority();
            $model->admin_id = $admin->id;
            $model->authority_id = $id;
            $model->save();
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '更新成功');
    }


}