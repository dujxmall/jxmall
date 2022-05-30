<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-18
 * Time: 22:51
 */

namespace app\forms\api\user;


use app\core\ApiCode;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\models\User;

class UserForm extends BaseModel
{
    public $user_id;
    public $remarks;
    public $mobile;
    public $birthday;

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['remarks', 'mobile','birthday'], 'string']
        ]; // TODO: Change the autogenerated stub
    }


    public function bind()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        /**
         * @var User $user
         */
        $user = \Yii::$app->user->identity;

        if (!$user) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '用户未登录');
        }
        $user->mobile = $this->mobile;
        if (!$user->save()) {
            return $this->responseErrorMsg($user);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '绑定成功');
    }
    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/3/12
     * Time：10:40
     * Note：设置生日
     */
    public function setBirthday()
    {
        /**
         * @var  User $user
         */
        $user = \Yii::$app->user->identity;

        if (!$this->birthday) {
            return ResponseHelper::send(ApiCode::CODE_FAIL, '请选择生日！');
        }
        $user->birthday = $this->birthday;

        $arr=explode('-',$this->birthday);

        $user->year=$arr[0];
        $user->month=$arr[1];
        $user->day=$arr[2];
        if (!$user->save()) {
            return $this->responseErrorMsg($user);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '设置成功!');
    }
}