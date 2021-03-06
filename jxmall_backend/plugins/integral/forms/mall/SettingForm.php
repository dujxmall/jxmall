<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/3/30
 * Time: 14:33
 * Note:
 */

namespace app\plugins\integral\forms\mall;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;

class SettingForm extends BaseModel
{
    public $is_sign_enabled; //是否启用积分签到
    public $day_integral; //单日签到积分
    public $is_continue;//是否开启连续签到奖励
    public $continue_day;//连续签到天数
    public $continue_integral;//连续签到积分
    public $is_enabled;
    public $is_invite_integral;//开启拉新积分
    public $invite_integral;//邀请新人获得多少积分

    public function rules()
    {
        return [
            [['is_sign_enabled', 'day_integral', 'is_continue', 'continue_day', 'continue_integral', 'is_enabled','is_invite_integral','invite_integral'], 'integer'],
        ]; // TODO: Change the autogenerated stub
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        OptionHelper::set('plugin_integral_setting', $this->attributes, $this->mall_id);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '保存成功');
    }


    public function search()
    {
        $setting = OptionHelper::get('plugin_integral_setting', $this->mall_id);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['setting' => $setting]);
    }

}