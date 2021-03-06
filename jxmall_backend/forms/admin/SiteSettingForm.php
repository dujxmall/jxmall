<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-29
 * Time: 8:54
 */

namespace app\forms\admin;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\models\BaseModel;
use app\models\Option;

class SiteSettingForm extends BaseModel
{
    public $logo;
    public $name;
    public $header_pic;
    public $login_pic;
    public $keywords;
    public $copyright;
    public $copyright_url;
    public $sub_name;
    public $record;
    public $auth_key;
    public $is_register;
    public $try_day;
    public $login_logo;
    public $domain;

    public $title;

    public function rules()
    {
        return [
            [['is_register', 'try_day'], 'integer'],
            [['logo', 'header_pic', 'login_pic', 'copyright_url', 'login_logo', 'domain'], 'string', 'max' => 255],
            [['name', 'sub_name', 'record', 'title'], 'string', 'max' => 45],
            [['auth_key'], 'string', 'max' => 128],
            [['keywords', 'copyright'], 'string', 'max' => 256]
        ]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        OptionHelper::set(Option::NAME_SYS_SETTING, $this->attributes, 0);
        OptionHelper::set('domain', $this->domain, 0);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => OptionHelper::get(Option::NAME_SYS_SETTING)]);
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $setting = OptionHelper::get(Option::NAME_SYS_SETTING, 0);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => $setting ?? null]);
    }

    public function baseInfo()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $setting = OptionHelper::get(Option::NAME_SYS_SETTING, 0);
        if ($setting) {
            $info['copyright'] = $setting['copyright'];
            $info['copyright_url'] = $setting['copyright_url'];
            $info['header_pic'] = $setting['header_pic'];
            $info['is_register'] = $setting['is_register'];
            $info['sub_name'] = $setting['sub_name'];
            $info['record'] = $setting['record'];
            $info['name'] = $setting['name'];
            $info['logo'] = $setting['logo'];
            $info['title'] = $setting['title'];
            $info['try_day'] = $setting['try_day'];
            $info['login_pic'] = $setting['login_pic'];
            $info['login_logo'] = $setting['login_logo'];
        }

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => $info ?? null]);
    }

}