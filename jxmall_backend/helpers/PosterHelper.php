<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-22
 * Time: 21:49
 */

namespace app\helpers;


class PosterHelper
{

    public static function getDefault()
    {
        $urlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl .
            '/statics/img/mall/poster/';
        return [
            'inviter' => [ //推广海报
                'bg_pic' => [
                    'url' => ImageHelper::getUrl('/assets/poster/poster-bg.png'),
                    'is_show' => 1,
                ],
                'head' => [
                    'is_show' => 1,
                    'size' => 60,
                    'top' => 10,
                    'left' => 10,
                    'file_type' => 'image',
                    'url' => ImageHelper::getUrl('/assets/poster/avatar.png')
                ],
                'qr_code' => [
                    'is_show' => 1,
                    'size' => 120,
                    'top' => 150,
                    'left' => 127,
                    'type' => '1',
                    'file_type' => 'image',
                    'url' => ImageHelper::getUrl('/assets/poster/qrcode.png')
                ],
                'name' => [
                    'is_show' => 1,
                    'font' => 20,
                    'top' => 30,
                    'left' => 80,
                    'color' => '#000',
                    'file_type' => 'text',
                    'text' => '用户昵称'
                ],
            ],
            'goods' => [ //商品海报
                'bg_pic' => [
                    'url' => ImageHelper::getUrl('/assets/poster/poster-bg.png'),
                    'is_show' => 1,
                ],
                'pic' => [
                    'url' => ImageHelper::getUrl('/assets/poster/default-goods.jpg'),
                    'is_show' => 1,
                    'width' => 750,
                    'height' => 750,
                    'top' => 0,
                    'left' => 0,
                    'file_type' => 'image',

                ],
                'name' => [
                    'is_show' => 1,
                    'font' => 30,
                    'top' => 780,
                    'left' => 20,
                    'color' => '#000',
                    'file_type' => 'text',
                    'text' => '商品名称'
                ],
                'price' => [
                    'is_show' => 1,
                    'font' => 20,
                    'top' => 830,
                    'left' => 20,
                    'color' => '#EB0909',
                    'file_type' => 'text',
                    'text' => '￥100.00'
                ],
                'head' => [
                    'is_show' => '1',
                    'size' => 150,
                    'top' => 900,
                    'left' => 20,
                    'file_type' => 'image',
                    'url' => ImageHelper::getUrl('/assets/poster/avatar.png')
                ],
                'nickname' => [
                    'is_show' => 1,
                    'font' => 20,
                    'top' => 910,
                    'left' => 210,
                    'color' => '#000',
                    'file_type' => 'text',
                    'text' => '用户昵称'
                ],
                'qr_code' => [
                    'is_show' => 1,
                    'size' => 250,
                    'top' => 900,
                    'left' => 480,
                    'type' => '1',
                    'file_type' => 'image',
                    'url' => ImageHelper::getUrl('/assets/poster/qrcode.png')
                ],
                'desc' => [
                    'is_show' => 1,
                    'width' => 260,
                    'font' => 16,
                    'top' => 1150,
                    'left' => 495,
                    'color' => '#777777',
                    'text' => '长按识别二维码进入',
                    'file_type' => 'text',
                ],
            ],
        ];
    }


}