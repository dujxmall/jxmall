<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-08-07
 * Time: 21:32
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\CacheHelper;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\helpers\SerializeHelper;
use app\models\BaseModel;

class MenuEditForm extends BaseModel
{

    public $check_keys;
    public $admin_id;

    public function rules()
    {
        return [
            [['admin_id'], 'integer'],
            [['check_keys', 'admin_id'], 'required'],
            [['check_keys'], 'safe'],
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $arr=array_values($this->check_keys);
        if(is_array($arr)){
            $arr=array_unique($arr);
        }
        OptionHelper::set('mall_menus_check_keys_' . $this->admin_id, $arr, 0);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '');
    }

}