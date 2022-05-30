<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-12-18
 * Time: 10:01
 */

namespace app\forms\api\mall;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\models\BaseModel;

class AlertAdvForm extends BaseModel
{
    public function search()
    {
        if (!$this->validate()) {
            return $this->responseErrorInfo();
        }
        $alert_data = OptionHelper::get('alert_data', \Yii::$app->mall->id);
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['alert_data' => $alert_data]);
    }

}