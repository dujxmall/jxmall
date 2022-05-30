<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 22:51
 */

namespace app\forms\mall\user;


use app\core\ApiCode;
use app\models\BaseModel;
use app\models\User;

class UserForm extends BaseModel
{
    public $user_id;
    public $remarks;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['remarks'], 'string']

        ]; // TODO: Change the autogenerated stub
    }


    public function remarksChange()
    {

        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $user = User::findOne(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id, 'id' => $this->user_id]);
        if (!$user) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '用户不存在！');
        }
        $user->remarks = $this->remarks;
        if (!$user->save()) {
            return $this->responseErrorMsg($user);
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '保存成功');
    }

    public function bind()
    {
    }

}
