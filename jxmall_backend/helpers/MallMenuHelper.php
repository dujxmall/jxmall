<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2021-08-07
 * Time: 22:42
 */

namespace app\helpers;


use app\models\Plugin;

class MallMenuHelper
{
    public static function defaultCheckKeys($mall_id = 0)
    {
        $keys = [
            "/mall/data"
        ];
        if (!$mall_id) {
            return $keys;
        }
        return $keys;
    }

    public static function defaultMenuList($mall_id = 0)
    {
        $pluginMenus = self::getPluginMenus();
        $menus = [
            [
                'path' => '/mall',
                'name' => '商城',
                'icon' => 'el-icon-house',
                'children' =>
                    [
                        [
                            'name' => '商城设置',
                            'path' => '/mall/index',
                        ],
                        [
                            'name' => '数据概况',
                            'path' => '/mall/data',
                            'is_checked' => true,
                            'checked' => true,
                            'disabled' => true,
                        ],
                        [
                            'path' => '/mall/diy',
                            'name' => '商城装修',
                        ],
                        [
                            'path' => '/mall/hot/image/list',
                            'name' => '热图模板',
                        ],
                        [
                            'path' =>'/mall/hot/image',
                            'name' => '热图编辑',
                        ],
                        [
                            'path' => '/mall/banner',
                            'name' => '轮播图',
                        ],
                        [
                            'path' => '/mall/banner/edit',
                            'name' => '轮播图编辑',
                        ],
                        [
                            'path' => '/mall/alert/adv',
                            'name' => '弹窗广告',
                        ],

                        [
                            'path' => '/mall/user',
                            'name' => '个人中心',
                        ],
                        [
                            'path' => '/mall/manage',
                            'name' => '推广中心',

                        ],
                        [
                            'path' => '/mall/page',
                            'name' => '页面路径',

                        ],
                        [
                            'path' => '/mall/diy-edit',
                            'name' => 'DIY编辑',
                            'hidden' => true,

                        ],
                        [
                            'path' => '/mall/freight',
                            'name' => 'FreightEdit',

                        ],
                        [
                            'path' => '/mall/freight-edit',
                            'name' => '运费编辑',
                        ],
                        [
                            'path' => '/mall/eorder',
                            'name' => '电子面单',
                        ],
                        [
                            'path' => '/mall/eorder-edit',
                            'name' => '电子面单编辑',
                            'hidden' => true,
                        ],
                        [
                            'path' => '/mall/payment',
                            'name' => '支付设置',

                        ],
                        [
                            'path' => '/mall/upload',
                            'name' => '上传设置',
                        ],
                        [
                            'path' => '/mall/feedback',
                            'name' => '用户反馈',
                        ],
                        [
                            'path' =>'/mall/checking',
                            'name' => '版本审核',
                        ],
                    ]
            ],
            [
                'path' => '/poster',
                'name' => '海报',
                'icon' => 'el-icon-hot-water',
                'children' =>
                    [
                        [
                            'path' => '/poster/inviter',
                            'name' => '推广海报'

                        ],
                        [
                            'path' => '/poster/goods',
                            'name' => '商品海报',
                        ],
                        [
                            'path' => '/poster/friend/poster',
                            'name' => '好友海报',
                        ]
                    ]
            ],
            [
                'path' => '/goods',
                'name' => '商品',
                'icon' => 'el-icon-goods',
                'children' => [
                    [
                        'path' => '/goods/index',
                        'name' => '商品列表',
                    ],
                    [
                        'path' => '/goods/edit',
                        'name' => '商品编辑',

                    ],
                    [
                        'path' => '/goods/cat-edit',
                        'name' => '商品编辑',
                        'hidden' => true,
                    ],
                    [
                        'path' => '/goods/cat',
                        'name' => '商品分类',
                    ],
                ]
            ],
            [
                'path' => '/order',
                'name' => '订单',
                'icon' => 'el-icon-document',
                'children' => [
                    [
                        'path' => '/order/index',
                        'name' => '订单列表',
                    ],
                    [
                        'path' => '/order/refund',
                        'name' => '售后订单',
                    ],
                    [
                        'path' => '/order/detail',
                        'name' => '订单详情',
                        'hidden' => true,
                    ],
                    [
                        'path' => '/order/express/log',
                        'name' => '发货记录',
                    ],
                ]
            ],
            [
                'path' => '/user',
                'name' => '用户',
                'icon' => 'el-icon-user',
                'children' => [
                    [
                        'path' => '/user/index',
                        'name' => '用户管理',
                    ],
                    [
                        'path' => '/user/edit',
                        'name' => '用户编辑',
                        'hidden' => true,

                    ],
                    [
                        'path' => '/user/level',
                        'name' => '用户等级',

                    ],
                    [
                        'path' => '/user/level-edit',
                        'name' => '等级编辑',
                        'hidden' => true,
                    ],
                    [
                        'path' => '/user/setting',
                        'name' => '推广设置',
                    ],
                ]
            ],
            [
                'path' => '/market',
                'name' => '营销',
                'icon' => 'el-icon-present',
                'children' => [
                    [
                        'path' => '/market/coupon',
                        'name' => '优惠券列表',
                    ],
                    [
                        'path' => '/market/coupon/edit',
                        'name' => '编辑优惠券',
                        'hidden' => true,

                    ],

                ]
            ],
            [
                'path' => '/finance',
                'name' => '财务',
                'icon' => 'el-icon-wallet',
                'children' => [
                    [
                        'path' => '/finance/cash',
                        'name' => '佣金提现',
                    ],
                    [
                        'path' => '/finance/balance/log',
                        'name' => '余额记录',
                    ],
                    [
                        'path' => '/finance/price/log',
                        'name' => '佣金记录',
                    ],
                    [
                        'path' => '/finance/integral/log',
                        'name' => '积分记录',
                    ],
                    [
                        'path' => '/finance/cash/setting',
                        'name' => '提现设置',
                    ],
                    [
                        'path' => '/finance/charge',
                        'name' => '充值记录',
                    ],
                ]
            ],
            [
                'path' => '/plugins',
                'name' => '应用',
                'icon' => 'el-icon-receiving',
                'children' =>$pluginMenus
            ],
            [
                'path' => '/platform',
                'name' => '平台',
                'icon' => 'el-icon-mobile',
                'children' =>
                    [
                        [
                            'path' => '/platform/wechat',
                            'name' => '公众号',
                        ],
                        [
                            'path' => '/wechat/menu',
                            'name' => '菜单编辑',
                            'hidden' => true,
                        ],
                        [
                            'path' => '/platform/mpwx',
                            'name' => '小程序',
                        ],
                        [
                            'path' => '/platform/wxapp',
                            'name' => '开放平台',
                        ],
                    ]
            ],
        ];

        if (!$mall_id) {
            return $menus;
        }
        return $menus;
    }


    public static function getPluginMenus()
    {
        $plugins = Plugin::find()->where(['is_delete' => 0])->all();
        $arr = [];
        foreach ($plugins as $item) {
            /**
             * @var \app\plugins\Plugin $plugin
             */
            $plugin = \Yii::$app->plugin->getPlugin($item->name);
            $arr[] = $plugin->getAdminMenus();
        }
        return $arr;
    }

}
