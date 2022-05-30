<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-17
 * Time: 16:59
 */

namespace app\forms\api\mall;


use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;

class MallSettingForm extends BaseModel
{
    public function getAbout()
    {
        $res = OptionHelper::get('mall_setting', $this->mall_id);
        if (!$res) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '未设置');
        }
        if (!$res['about']) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '未设置关于内容');
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['about' => $res['about']]);
    }

    public function getSetting()
    {
        $res = OptionHelper::get('mall_setting', $this->mall_id);
        if (!$res) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '未设置');
        }
        $setting = [
            'name' => $res['name'],
            'phone_icon' => $res['phone_icon'],
            'contact_icon' => $res['contact_icon'],
            'about' => $res['about'],
            'logo' => $res['logo'],
            'login_pic' => $res['login_pic'],
            'detail' => $res['detail'],
            'tel' => $res['tel'],
            'show_contact' => $res['show_contact'],
            'show_phone' => $res['show_phone'],
            'show_nav' => $res['show_nav'],
            'nav_icon' => $res['nav_icon'],
            'address' => $res['address'],
            'lon' => $res['lon'],
            'lat' => $res['lat'],
            'is_home_login' => $res['is_home_login'],
            'is_login_mobile' => $res['is_login_mobile'],
            'is_ban_cancel'=>$res['is_ban_cancel'],
            'is_show_add_cart'=>$res['is_show_add_cart']
        ];
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', [
            'setting' => $setting,
            'tabbar' => $this->getTabbar(),
            'checking_versions' => $this->getCheckingVersions()
        ]);
    }

    public function getTabbar()
    {
        $userPageData = OptionHelper::get('user_center_setting', \Yii::$app->mall->id);
        if (!$userPageData) {
            $tabbar = $this->getDefaultTabbar();
        } else {
            $tabbar = $userPageData['tabbar'];
        }
        return $tabbar;
    }

    public function getCheckingVersions()
    {
        $versions = OptionHelper::get('checking_versions', $this->mall_id);
        return ResponseHelper::send(ApiCode::CODE_SUCCESS, '', ['versions' => $versions]);
    }

    private function getDefaultTabbar()
    {

        return [
            'color' => '#E41F19',
            'list' => [
                [
                    'name' => '首页',
                    'url' => '/pages/index/index',
                    'icon' => ImageHelper::getUrl('/assets/default/tabbar/index.png'),
                    'active_icon' => ImageHelper::getUrl('/assets/default/tabbar/index-active.png')
                ],
                [
                    'name' => '分类',
                    'url' => '/pages/cat/cat',
                    'icon' => ImageHelper::getUrl('/assets/default/tabbar/cat.png'),
                    'active_icon' => ImageHelper::getUrl('/assets/default/tabbar/cat-active.png')
                ],
                [
                    'name' => '购物车',
                    'url' => '/pages/cart/cart',
                    'icon' => ImageHelper::getUrl('/assets/default/tabbar/cart.png'),
                    'active_icon' => ImageHelper::getUrl('/assets/default/tabbar/cart-active.png')

                ],
                [
                    'name' => '我的',
                    'url' => '/pages/user/user',
                    'icon' => ImageHelper::getUrl('/assets/default/tabbar/my.png'),
                    'active_icon' => ImageHelper::getUrl('/assets/default/tabbar/my-active.png')
                ],
            ]
        ];

    }


}
