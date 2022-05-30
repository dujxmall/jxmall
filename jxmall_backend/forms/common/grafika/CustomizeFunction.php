<?php
/**
 * @link:http://www.gdqijianshi.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙
 * 海报配置
 * Author: zal
 * Date: 2020-04-18
 * Time: 15:11
 */

namespace app\forms\common\grafika;

use app\forms\common\QrCodeCommon;
use app\models\User;

trait CustomizeFunction
{
    /**
     * @param array $option 海报配置
     * @param array $params 小程序码配置
     * @param GrafikaOption $model
     * @return string 缓存路径
     * @throws \Exception
     */
    public function qrcode(array $option, array $params, GrafikaOption $model): string
    {
        $code = (new QrCodeCommon())->getQrCode($params[0], $params[1], $params[2]);
        $code_path = self::saveTempImage($code['file_path']);
        if ($option['qr_code']['type'] == 1) {
            $code_path = self::avatar($code_path, $model->temp_path, $option['qr_code']['size'], $option['qr_code']['size']);
        }
        return $model->destroyList($code_path);
    }

    /**
     * @param GrafikaOption $model
     * @return string
     */
    public static function head(GrafikaOption $model): string
    {
        $user = User::findOne(['id' => \Yii::$app->user->id]);
        $avatar = self::avatar(self::saveTempImage($user->avatar_url, $model->default_avatar_url), $model->temp_path, 0, 0);
        return $model->destroyList($avatar);
    }
}