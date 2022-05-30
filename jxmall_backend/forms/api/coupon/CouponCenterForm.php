<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-12
 * Time: 12:16
 */

namespace app\forms\api\coupon;


use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\models\BaseModel;

class CouponCenterForm extends BaseModel
{

    public function search()
    {


        $setting = [
            'banner' => ImageHelper::getUrl('/assets/default/coupon/banner_coupon.png'),
            'failure' => ImageHelper::getUrl('/assets/default/coupon/failure.png'),
            'used' => ImageHelper::getUrl('/assets/default/coupon/used.png'),
            'bg' => ImageHelper::getUrl('/assets/default/coupon/bg.png'),

        ];
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => $setting]);

    }

}