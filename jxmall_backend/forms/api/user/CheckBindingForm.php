<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-04-27
 * Time: 16:14
 */

namespace app\forms\api\user;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;

class CheckBindingForm extends BaseModel
{

    public function search()
    {

        $option = OptionHelper::get('mall_setting', \Yii::$app->mall->id);
        if (!$option || !isset($option['is_binding']) || !$option['is_binding']) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['need_bind' => 0]);
        }

        if (\Yii::$app->user->isGuest) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['need_bind' => 0]);
        }
        if (!\Yii::$app->user->identity->mobile) {
            return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['need_bind' => 1]);
        }
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['need_bind' => 0]);
    }

}
