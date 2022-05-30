<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-10
 * Time: 13:58
 */

namespace app\forms\api\user;


use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\helpers\OptionHelper;
use app\models\BaseModel;
use app\models\CommonOrder;
use app\models\GoodsFavorite;
use app\models\GoodsFootmark;
use app\models\Level;
use app\models\MchFavorite;
use app\models\Order;
use app\models\User;
use app\models\UserCoupon;

class IndexDataForm extends BaseModel
{
    public function getData()
    {
        /**
         * @var User $user ;
         */
        $user = \Yii::$app->user->identity;
        $user_info = [];
        $user_info['level'] = 0;
        $user_info['order_status_0'] = 0;
        $user_info['order_status_1'] = 1;
        $user_info['order_status_2'] = 2;
        $user_info['order_status_3'] = 3;
        $user_info['order_status_4'] = 4;
        if ($user) {
            $user_info['id'] = $user->id;
            $user_info['nickname'] = $user->nickname;
            $user_info['avatar_url'] = $user->avatar_url;
            $user_info['access_token'] = $user->access_token;
            $user_info['total_money'] = $user->total_money;
            $user_info['total_price'] = $user->total_price;
            $user_info['mobile'] = $user->mobile;
            $user_info['is_inviter'] = $user->is_inviter;
            $user_info['integral'] = $user->integral;
            $user_info['birthday'] = $user->birthday;
            $user_info['price'] = $user->price;
            $user_info['balance'] = $user->money;
            $level = Level::findOne(['level' => $user->level, 'mall_id' => $user->mall_id, 'is_delete' => 0, 'enabled' => 1]);
            if (!$level) {
                $user_info['level_name'] = '普通用户';
            } else {
                $user_info['level_name'] = $level->name;
                $user_info['level'] = $user->level;
                $user_info['level_icon'] = $level->pic_url;
            }
            $coupon = UserCoupon::find()->where(['user_id' => $user->id, 'is_delete' => 0, 'status' => 0])->count();
            $user_info['coupon'] = $coupon ?? 0;
            $goods_favorite = GoodsFavorite::find()->where(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])->count();
            $user_info['goods_favorite'] = $goods_favorite ?? 0;
            $mch_favorite = MchFavorite::find()->where(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])->count();
            $user_info['mch_favorite'] = $mch_favorite ?? 0;
            $goods_footmark = GoodsFootmark::find()->where(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])->count();
            $user_info['goods_footmark'] = $goods_footmark ?? 0;
            $common_order_num = CommonOrder::find()->where(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'status' => 1])->count();
            $user_info['order_num'] = $common_order_num ?? 0;
        } else {
            $user_info['avatar_url'] = ImageHelper::getUrl('/assets/default/user/avatar.png');
            $user_info['integral'] = 0;
            $user_info['coupon'] = 0;
            $user_info['price'] = 0;
            $user_info['balance'] = 0;
            $user_info['goods_favorite'] = 0;
            $user_info['mch_favorite'] = 0;
            $user_info['order_num'] = 0;
            $user_info['goods_footmark'] = 0;
            $user_info['level_name'] = '普通用户';
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['user' => $user_info]);
    }


    public function getPageData()
    {
        $userPageData = OptionHelper::get('user_center_setting', \Yii::$app->mall->id);
        if (!$userPageData) {
            $userPageData = $this->getPageDefault();
        }
        if(!\Yii::$app->user->isGuest){
            $order = $userPageData['order'];
            $order['status_0']['num'] = Order::find()->andWhere(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'status' => 0])->count();
            $order['status_1']['num'] = Order::find()->andWhere(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'status' => 1])->count();
            $order['status_2']['num'] = Order::find()->andWhere(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'status' => 2])->count();
            $order['status_3']['num'] = Order::find()->andWhere(['user_id' => \Yii::$app->user->identity->id, 'is_delete' => 0, 'status' => 3])->count();
            $userPageData['order'] = $order;
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['page_data' => $userPageData]);
    }

    private function getPageDefault()
    {
        $default['tabbar'] = [
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
        $default['header'] = [
            'user_bg' => ImageHelper::getUrl('/assets/default/user/user_bg.png'),
            'avatar' => ImageHelper::getUrl('/assets/default/user/avatar.png'),
            'goods' => [
                'is_show' => 1,
                'name' => '商品收藏'
            ],
            'order' => [
                'is_show' => 1,
                'name' => '成交订单'
            ],
            'footmark' => [
                'is_show' => 1,
                'name' => '足迹'
            ],
        ];
        $default['order'] = [
            'status_0' => [
                'name' => '待付款',
                'url' => '/pages/order/order?status=0',
                'icon' => ImageHelper::getUrl('/assets/default/user/order0.png'),
                'num' => 0
            ],
            'status_1' => [
                'name' => '待发货',
                'url' => '/pages/order/order?status=1',
                'icon' => ImageHelper::getUrl('/assets/default/user/order1.png'),
                'num' => 0
            ],
            'status_2' => [
                'name' => '待收货',
                'url' => '/pages/order/order?status=2',
                'icon' => ImageHelper::getUrl('/assets/default/user/order2.png'),
                'num' => 0
            ],
            'status_3' => [
                'name' => '待评价',
                'url' => '/pages/order/order?status=3',
                'icon' => ImageHelper::getUrl('/assets/default/user/order3.png'),
                'num' => 0
            ],
            'status_4' => [
                'name' => '退款/售后',
                'url' => '/pages/order/order?status=4',
                'icon' => ImageHelper::getUrl('/assets/default/user/order4.png'),
                'num' => 0
            ],
        ];

        $default['menus'] = [
            'name' => '常用工具',
            'type' => 0,
            'list' => [
                [
                    'name' => '待付款',
                    'num' => 0,
                    'url' => '/pages/order/order?status=0',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order0.png')
                ],
                [
                    'name' => '待发货', 'num' => 0,
                    'url' => '/pages/order/order?status=1',
                    'icon' => ImageHelper::getUrl('/assets/default/user/order1.png')
                ],
                [
                    'name' => '待收货',
                    'url' => '/pages/order/order?status=2', 'num' => 0,
                    'icon' => ImageHelper::getUrl('/assets/default/user/order2.png')
                ],
                [
                    'name' => '待评价',
                    'url' => '/pages/order/order?status=3', 'num' => 0,
                    'icon' => ImageHelper::getUrl('/assets/default/user/order3.png')
                ],
                [
                    'name' => '退款/售后',
                    'url' => '/pages/order/order?status=4', 'num' => 0,
                    'icon' => ImageHelper::getUrl('/assets/default/user/order4.png')
                ],
            ]
        ];
        return $default;
    }

}
