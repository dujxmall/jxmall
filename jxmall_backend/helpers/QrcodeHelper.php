<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-24
 * Time: 15:16
 */

namespace app\helpers;


use Endroid\QrCode\QrCode;

class QrcodeHelper
{
    /**
     * Created by PhpStorm.
     * User：ganxi
     * Date：2022/5/20
     * Time：9:46
     * Note：
     */
    public static function create($data, $saveName = null)
    {
        if ($saveName) {
            $saveName = $saveName . md5($data) . '.png';//生成的二维码保存地址
        } else {
            $saveName = uniqid() . md5($data) . '.png';//生成的二维码保存地址
        }
        $file = \Yii::getAlias('@webroot/qrcode/') . $saveName;//生成的二维码保存地址
        if (!file_exists($file)) {
            if (FileHelper::createDirectory(dirname($file))) {
                $qrCode = new QrCode($data);
                $qrCode->setSize(200);
                $qrCode->setMargin(10);
                $qrCode->writeFile($file);
            }
        }

        return ['path' => $file, 'file_name' => '/web/qrcode/' . $saveName];
    }
}