<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-13
 * Time: 9:13
 */

namespace app\forms\api\mall;


use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\Tabbar;

class TabbarForm extends BaseModel
{

    public function search()
    {
        $userPageData = OptionHelper::get('user_center_setting', \Yii::$app->mall->id);
        if (!$userPageData) {
            $tabbar = $this->getDefault();
        } else {
            $tabbar = $userPageData['tabbar'];

        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['tabbar' => $tabbar]);
    }

    private function getDefault()
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