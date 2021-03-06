<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-26
 * Time: 9:13
 */

namespace app\forms\mall\link;


use app\core\ApiCode;
use app\models\BaseModel;

class LinkForm extends BaseModel
{

    public $type; //mall goods diy cat

    public function rules()
    {
        return [
            [['type'], 'string']

        ]; // TODO: Change the autogenerated stub
    }

    public function getList()
    {

        $list = [];
        //获取商城链接
        if ($this->type == 'mall') {

            $list = $this->getMallLinks();
        }
        if ($this->type == "plugin") {
            $list = $this->getPluginLinks();
        }

        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);
    }

    private function getPluginLinks()
    {


        $menus = [];

        $list = \Yii::$app->plugin->list;

        foreach ($list as $item) {
            $plugin = \Yii::$app->plugin->getPlugin($item->name);
            $group = ['name' => $item->label, 'links' => $plugin->getMenus()];
            $menus[] = $group;
        }

        unset($item);
        return $menus;
    }

    private function getMallLinks()
    {
        return [
            [
                'name' => '商城页面',
                'links' => [
                    [
                        'name' => '商城首页',
                        'url' => '/pages/index/index',
                    ],
                    [
                        'name' => '分类含有商品',
                        'url' => '/pages/cat-goods/cat-goods',
                    ],
                    [
                        'name' => '三级分类',
                        'url' => '/pages/cat/cat',
                    ],
                    [
                        'name' => '二级分类',
                        'url' => '/pages/cat2/cat2',
                    ],
                    [
                        'name' => '全部商品',
                        'url' => '/pages/goods-list/goods-list',
                    ],
                    [
                        'name' => '购物车',
                        'url' => '/pages/cart/cart',
                    ],
                    [
                        'name' => '搜索',
                        'url' => '/pages/search/search',
                    ],
                    [
                        'name' => '关于我们',
                        'url' => '/pages/about/about',
                    ],
                ],
            ],
            [
                'name' => '会员中心',
                'links' => [
                    [
                        'name' => '会员中心',
                        'url' => '/pages/user/user',
                    ],
                    [
                        'name' => '意见反馈',
                        'url' => '/pages/feedback/feedback',
                    ],
                    [
                        'name' => '我的团队',
                        'url' => '/pages/team/team',
                    ],
                    [
                        'name' => '推广中心',
                        'url' => '/pages/manage/manage',
                    ],
                    [
                        'name' => '佣金提现',
                        'url' => '/pages/cash/cash',
                    ],
                    [
                        'name' => '我的订单(全部)',
                        'url' => '/pages/order/order',
                    ],
                    [
                        'name' => '待付款订单',
                        'url' => '/pages/order/order?status=0',
                    ],
                    [
                        'name' => '待发货订单',
                        'url' => '/pages/order/order?status=1',
                    ],
                    [
                        'name' => '待收货订单',
                        'url' => '/pages/order/order?status=2',
                    ],
                    [
                        'name' => '待评价订单',
                        'url' => '/pages/order/order?status=3',
                    ],
                    [
                        'name' => '维权订单',
                        'url' => '/pages/order/order?status=4',
                    ],
                    [
                        'name' => '我的收藏',
                        'url' => '/pages/collect/collect',
                    ],
                    [
                        'name' => '我的足迹',
                        'url' => '/pages/footmark/footmark',
                    ],
                    [
                        'name' => '会员充值',
                        'url' => '/pages/charge/charge',
                    ],
                    [
                        'name' => '余额明细',
                        'url' => '/pages/balance-log/balance-log',
                    ],
                    [
                        'name' => '积分明细',
                        'url' => '/pages/integral-log/integral-log',
                    ],
                    [
                        'name' => '佣金记录',
                        'url' => '/pages/price-log/price-log',
                    ],
                    [
                        'name' => '余额提现',
                        'url' => '/pages/balance-cash/balance-cash',
                    ],
                    [
                        'name' => '我的资料',
                        'url' => '/pages/setting/setting',
                    ],
                    [
                        'name' => '收货地址管理',
                        'url' => '/page/address/address',
                    ],

                ],
            ],

            [
                'name' => '我的优惠券/领券中心',
                'links' => [
                    [
                        'name' => '领券中心',
                        'url' => '/pages/coupon/coupon',
                    ],
                    [
                        'name' => '我的优惠券',
                        'url' => '/pages/coupon/coupon?status=1',
                    ],
                ],
            ],
        ];

    }

}