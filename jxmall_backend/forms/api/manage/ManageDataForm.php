<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-11-04
 * Time: 20:25
 */

namespace app\forms\api\manage;

use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\helpers\OptionHelper;
use app\helpers\SerializeHelper;
use app\models\BaseModel;
use app\models\User;

class ManageDataForm extends BaseModel
{

    public function getData()
    {

        /**
         * @var User $user ;
         */
        $user = \Yii::$app->user->identity;
        $info = [
            "money" => $user->money,
            'price' => $user->price,
            'cashing' => 0,
            'cash' => 0,
            'integral' => $user->integral,
            'total_price' => $user->total_price,
            'avatar_url' => $user->avatar_url,
            'nickname' => $user->nickname
        ];
        \Yii::warning(SerializeHelper::encode($user));
        $parent = User::getBaseInfo($user->parent_id);
        if ($parent) {
            $info['parent_name'] = $parent->nickname;
        } else {
            $info['parent_name'] = '平台';
        }

        $setting = OptionHelper::get('user_manage_setting', \Yii::$app->mall->id);
        if (!$setting) {
            $setting = $this->getPageDefault();
        }

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['setting' => $setting, 'info' => $info]);


    }

    private function getPageDefault()
    {

        $default['header'] = [
            'parent_label' => '推荐人',
            'user_bg' => ImageHelper::getUrl('/assets/default/user/user_bg.png'),
            'avatar' => ImageHelper::getUrl('/assets/default/user/avatar.png'),
            'qrcode' => ImageHelper::getUrl('/assets/default/images/qrcode.png'),
            'balance' => [
                'name' => '余额'
            ],
            'price' => [
                'name' => '佣金'
            ],
            'integral' => [
                'name' => '积分'
            ],
        ];


        $default['menus'] = [
            'name' => '常用工具',
            'type' => 0,
            'list' => [
                [
                    'name' => '待付款',
                    'url' => '/pages/order/order?status=0',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order0.png')
                ],
                [
                    'name' => '待发货',
                    'url' => '/pages/order/order?status=1',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order1.png')
                ],
                [
                    'name' => '待收货',
                    'url' => '/pages/order/order?status=2',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order2.png')
                ],
                [
                    'name' => '待评价',
                    'url' => '/pages/order/order?status=3',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order3.png')
                ],
                [
                    'name' => '退款/售后',
                    'url' => '/pages/order/order?status=4',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order4.png')
                ],
            ]
        ];
        $default['finance'] = [
            'name' => '我的资产',
            'type' => 0,
            'total' => [
                'name' => '累计佣金',

            ],
            'price' => [
                'name' => '佣金',

            ],
            'cashing' => [

                'name' => '提现中',

            ],
            'cash' => [
                'name' => '已提现',

            ],

        ];

        return $default;

    }
}