<?php
/**
 * @link=>http=>//www.dujxmall.com/
 * @copyright=> Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author=> ganxiaohao
 * Date=> 2020-11-11
 * Time=> 10=>12
 */

namespace app\forms\mall\mall;


use app\core\ApiCode;
use app\helpers\ImageHelper;
use app\models\BaseModel;

class DiyForm extends BaseModel
{

    public function getComponents()
    {
        $list = [
            [
                'is_fixed' => 0,//固定组件一个页面只能有一个
                'name' => 'search-bar',
                'display_name' => '搜索',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/search.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'blank',
                'display_name' => '占位块',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/blank.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'notice',
                'display_name' => '公告',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/notice.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'img-group',
                'display_name' => '图片魔方',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/magic.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'banner',
                'display_name' => '轮播图',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/banner.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'title-bar',
                'display_name' => '标题栏',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/title.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'navbar',
                'display_name' => '导航按钮',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/nav.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'goods-group',
                'display_name' => '商品组',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/goods.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'custom-goods',
                'display_name' => '模拟商品',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/goods.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'video-block',
                'display_name' => '视频',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/video.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'pt-block',
                'display_name' => '拼团',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/pt.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'hot-image',
                'display_name' => '热点图',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/hot-image.png"),
                'data' => null
            ],
            [
                'is_fixed' => 0,
                'name' => 'float-button',
                'display_name' => '悬浮按钮',
                'active' => false,
                'icon' => ImageHelper::getUrl("/assets/diy/fab.png"),
                'data' => null
            ],
        ];
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', ['list' => $list]);

    }
}
